<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Review;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Toplan\Sms\Facades\SmsManager;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->only(['showLoginForm', 'login']);
        $this->middleware('auth')->only(['avatar', 'name_update', 'skin_update']);
    }

    public function login(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'mobile'     => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
        ]);

        if ($validator->fails()) {
            //验证失败后清空存储的发送状态，防止用户重复试错
//             SmsManager::forgetState();
            return response()->json($validator->messages(),422);
        }*/

        //上面的不要，只要下面这部分
        $request->validate([
            'mobile'     => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
        ]);

        $user = User::firstOrCreate(['mobile' => $request->mobile],
            [
                'name' => array_random(['汀兰', '君撷', '杜若', '画玺', '德音', '雅南', '予心', '如英', '疏影', '晴岚','采苓']),
                'avatar' => config('app.url').'/avatars/default.jpg'
            ]);


        Auth::login($user, true);

//        session()->flash('message','登录成功');

        return ['login' => true];
    }

    public function logout()
    {
        Auth::logout();
        return back();
    }


    public function show(int $user_id)
    {

        $user = Cache::rememberForever('users-' . $user_id, function () use ($user_id) {
            return User::find($user_id, ['id', 'name', 'avatar', 'skin']);
        });
//        dd($user->reviews_count);
        //用户点评若为0，则直接返回
        if ($user->reviews_count == 0) return view('users.show', compact('user'));

        $reviews = Cache::tags('users-' . $user_id . '-reviews')
            ->rememberForever('users-' . $user_id . '-reviews-' . request('page', 1), function () use ($user) {
                return $user->reviews()
                    ->select('id', 'user_id', 'product_id', 'cat_id', 'brand_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'updated_at')
                    ->with(['cat:id,name', 'brand:id,name,common_name', 'product:id,name,nick_name,rate', 'product.prices'])
                    ->latest('updated_at')
                    ->paginate(3);
            });


        //用户点评最多的品类--cats为映射数组，相同的话随机取一个，并算出占了几个
        $cats = Cache::rememberForever('users-' . $user_id . '-c', function () use ($user) {
            return array_count_values($user->cats()->pluck('cats.name')->all());
        });
        if (filled($cats)) {
            //先取最大数，再取出key，key即为catname
            $most_cat_count = max($cats);
            $most_cat = array_random(array_keys($cats, $most_cat_count));
        }

        //用户点评最多的品牌--brands为映射数组，相同的话随机取一个，并算出占了几个
        $brands = Cache::rememberForever('users-' . $user_id . '-b', function () use ($user) {
            return array_count_values($user->brands()->pluck('brands.name')->all());
        });
        $most_brand_count = max($brands);
        $most_brand = array_random(array_keys($brands, $most_brand_count));


        //如果用户登录，且有修改权限（即登录用户看的是自己的个人页面），且至少有1个回购点评
        if (optional(Auth::user())->can('update', $user) && $user->buys_count > 0) {

            $matches = Cache::tags('match-' . $user->id)->rememberForever('match-map-' . $user->id, function () use ($user) {

                $buy_products = $user->products()->where('buy', 0)->pluck('products.id')->all();//当前用户会回购的商品ids

                //映射数组
                return array_count_values(Review::where([['buy', 0], ['user_id', '<>', null], ['user_id', '<>', $user->id]])
                    ->whereIn('product_id', $buy_products)
                    ->pluck('user_id')->all());

                /*//和当前用户同样会回购同样商品的其他用户
                $match = $user->products()->where('buy', 0)->get()//当前用户会回购的商品
                ->flatMap->users//点评过这些商品的用户们，点评过同一个商品算一次记录
                ->where('id', '<>', $user->id)//去掉当前用户
                ->where('pivot.buy', 0)//只选会回购的那些记录
                ->pluck('id'); //投影出这些用户的id
                if (filled($match)) {
                    //找出[用户id=>次数]的数组映射
                    //先把匹配到的用户转化为数组->搜集每个用户分别出现的次数映射（关联数组，key为用户id，value为出现的此数）
                    //->转化为集合->用sort+reverse倒序排（为了不让key消失，不能用原生sort）
                    //只取前三名，可能没有三个-----type:数组----->这个数组传入视图，在循环中作为被除数以算出匹配率
                    return collect(array_count_values($match->all()))->sort()->reverse()->take(3)->all();
                }
                return [];*/
            });

            if(filled($matches)){

                $match_count = max($matches);//匹配到的最大次数

                $match_user = Cache::tags('match-' . $user->id)->rememberForever('match-user-' . $user->id, function () use ($matches, $match_count) {
                    $match_id = array_random(array_keys($matches, $match_count));//选其中一个匹配用户
                    return User::find($match_id, ['id', 'name', 'avatar', 'skin']);
                });
            }

            /*if (filled($match_map)) {

                $match_users = Cache::tags('match')->rememberForever('match-users-' . $user->id, function () use ($match_map) {
                    $match_ids = array_keys($match_map);
                    $match_users = User::select('id', 'name', 'avatar', 'skin')
                        ->whereIn('id', $match_ids)
                        ->orderByRaw('FIELD (id,' . implode(',', $match_ids) . ')')->get();
                    return $match_users;
                });
            }*/
            //做法1
            /* $match_users = collect(array_keys($match_map))->map(function ($id) {
                 return User::find($id, ['id', 'name', 'avatar', 'skin']);
             });*/
            //另一种做法
            /*$best_match_ids_str = implode(',', $best_match_ids);
            $best_match_users = User::whereIn('id', $best_match_ids)
                ->orderByRaw("FIELD (id,$best_match_ids_str)")->get();*/

        }

        return view('users.show', compact('user', 'reviews', 'cats', 'most_cat', 'most_cat_count', 'most_brand', 'most_brand_count', 'match_user', 'match_count'));
    }

    public function avatar(Request $request, User $user)
    {
//        return ['aa'=>'ok'];
        $request->validate(['file' => 'required|image|max:200|dimensions:max_width=400']);

        $this->authorize('update', $user);

        //获取文件--存储文件到avatars目录下--再转化为绝对路径
        $path = Storage::url($request->file->store('avatars'));
        $user->update(['avatar' => $path]);
        return ['path' => $path];

    }

    public function name_update(Request $request, User $user)
    {
        $request->validate(['name' => 'required|string|max:16']);

        $this->authorize('update', $user);

        $user->update(['name' => $request->name]);
//        return ['aa'=>'ok'];
    }

    public function skin_update(Request $request, User $user)
    {
        $request->validate(['skin' => 'required|in:0,1,2,3,4,5|integer']);

        $this->authorize('update', $user);

        $user->update(['skin' => $request->skin]);
//        return ['aa'=>'ok'];
    }

}
