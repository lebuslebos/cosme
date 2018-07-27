<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Repositories\UserRepository;
use App\Review;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Facades\Agent;
use Toplan\Sms\Facades\SmsManager;
use GuzzleHttp\Client;
use WXBizDataCrypt;
use Zhuzhichao\IpLocationZh\Ip;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest')->only(['login']);
        $this->middleware('auth')->only(['avatar', 'name_update', 'skin_update','logout']);
        $this->userRepository = $userRepository;
    }

    /**
     * 登录（pc）
     */
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
            'mobile' => 'required|confirm_mobile_not_change|confirm_rule:mobile_required',
            'verifyCode' => 'required|verify_code',
        ]);

        $user = User::firstOrCreate(['mobile' => $request->mobile],
            [
                'name' => array_random(['汀兰', '君撷', '杜若', '画玺', '德音', '雅南', '予心', '如英', '疏影', '晴岚', '采苓']),
                'avatar' => Storage::url('avatars/default.jpg'),
                'device'=>Agent::device(),
                'province' => Ip::find(request()->ip())[1],
                'city' => Ip::find(request()->ip())[2],
            ]);

        Auth::login($user, true);

        return ['login' => true];
    }



    //前端调用微信login接口，用code换openid和session_key
    /*public function api_pre_login()
    {
        $appid = config('common.wx_app_id');
        $secret = config('common.wx_app_secret');
        $code = request('code');//获取微信过来的数据
        //请求微信服务器
        $client = new Client();
        $res = $client->request('GET', 'https://api.weixin.qq.com/sns/jscode2session', [
            'query' => [
                'appid' => $appid,
                'secret' => $secret,
                'js_code' => $code,
                'grant_type' => 'authorization_code']
        ]);
        //获取微信服务器的响应(openid+session_key)
        $body = json_decode($res->getBody());
        $openid = $body->openid;
        $sessionKey = $body->session_key;
        Cache::forever('wx_secret',[$openid,$sessionKey]);
    }*/

    /**
     * 登录（微信）
     */
    public function api_login(Request $request)
    {
//        $wx_secret=Cache::pull('wx_secret');
//        $openid=$wx_secret[0];
        $request->validate([
            'code' => 'required|string',
            'encryptedData' => 'required|string',
            'iv' => 'required|string|size:24',
            'brand'=>'nullable|string',
            'model'=>'nullable|string'
        ]);
        //获取微信过来的数据
        $appid = config('common.wx_app_id');
        $secret = config('common.wx_app_secret');
        $code = request('code');
//        $sessionKey=$wx_secret[1];
        $encryptedData = request('encryptedData');
        $iv = request('iv');
//        Cache::forever('wx_secret',[$encryptedData,$iv]);测试用

        //请求微信服务器
        $client = new Client();
        $res = $client->request('GET', 'https://api.weixin.qq.com/sns/jscode2session', [
            'query' => [
                'appid' => $appid,
                'secret' => $secret,
                'js_code' => $code,
                'grant_type' => 'authorization_code']
        ]);
        //获取微信服务器的响应(openid+session_key)
        $body = json_decode($res->getBody());
        $openid = $body->openid;
        $sessionKey = $body->session_key;

//        Cache::forever($openid, $sessionKey);

        //用appid和sessionKey实例化对象
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        //解密出手机号信息，赋值给$data，返回错误码
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode == 0) {
            $phone = json_decode($data)->purePhoneNumber;
            $user = User::where('mobile', $phone)->first();//试图查找有无此用户
            if (blank($user)) {
                $user = User::create([
                    'name' => array_random(['汀兰', '君撷', '杜若', '画玺', '德音', '雅南', '予心', '如英', '疏影', '晴岚', '采苓']),
                    'mobile' => $phone,
                    'avatar' => Storage::url('avatars/default.jpg'),
                    'device'=>request('brand'),
                    'model'=>request('model'),
                    'province' => Ip::find(request()->ip())[1],
                    'city' => Ip::find(request()->ip())[2],
                    'openid' => $openid
                ]);
            } else {
                $user->update(['openid' => $openid]);
            }
            /*$user = User::firstOrCreate(['mobile' => $phone],
                [
                    'name' => array_random(['汀兰', '君撷', '杜若', '画玺', '德音', '雅南', '予心', '如英', '疏影', '晴岚', '采苓']),
                    'avatar' => Storage::url('avatars/default.jpg'),
                    'province' => Ip::find(request()->ip())[1],
                    'city' => Ip::find(request()->ip())[2],
                    'openid' => $openid
                ]);*/

            Cache::forever($openid, $user);

            //展示用户的已有数据
            return $this->userRepository->wx_user($user, $openid);

        } else {
            return compact('errCode');
        }
    }

    public function logout()
    {
        Auth::logout();
        return back();
    }

    /**
     * 展示个人页（pc）
     */
    public function show(int $user_id)
    {

        $user = $this->userRepository->user($user_id);

        //用户点评若为0，则直接返回
        if ($user->reviews_count == 0) return view('users.show', compact('user'));

        $reviews = $this->userRepository->reviews($user_id, $user);

        //用户点评最多的品类--cats为映射数组，相同的话随机取一个，并算出占了几个
        $cats = $this->userRepository->cats($user_id, $user);
        $most_cat_count = max($cats);//先取最大数，再取出key，key即为catname
        $most_cat = array_random(array_keys($cats, $most_cat_count));

        //用户点评最多的品牌--brands为映射数组，相同的话随机取一个，并算出占了几个
        $brands = $this->userRepository->brands($user_id, $user);
        $most_brand_count = max($brands);
        $most_brand = array_random(array_keys($brands, $most_brand_count));


        //如果用户登录，且有修改权限（即登录用户看的是自己的个人页面），且至少有1个回购点评
        if (optional(Auth::user())->can('update', $user) && $user->buys_count > 0) {

            $matches = $this->userRepository->matches($user_id,$user);

            if (filled($matches)) {

                $match_count = max($matches);//匹配到的最大次数

                $match_user = $this->userRepository->match_user($user_id,$matches,$match_count);
            }
            /*if (filled($match_map)) {

                $match_users = Cache::tags('match')->rememberForever('match-users-' . $user_id, function () use ($match_map) {
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

    /**
     * 展示我的页面（微信）
     */
    public function api_show(string $openid)
    {
        //虽然第一次登录的时候已经把key：openid，val：user_id存入了缓存，但为防缓存丢失，若无的话继续取一次
        $user = $this->userRepository->get_user($openid);

        if ($user) return $this->userRepository->wx_user($user, $openid);
    }
    //看别人的页面
    public function api_other_show(int $user_id)
    {
        $user = $this->userRepository->user($user_id);
        if ($user->reviews_count == 0) return compact('user');

        $reviews = $this->userRepository->reviews($user_id, $user);
        $reviews->withPath(config('common.url').'api/other_users/'.$user_id);
//        $reviews->withPath('other_users/'.$user_id);

        $cats = $this->userRepository->cats($user_id, $user);
        $most_cat_count = max($cats);
        $most_cat = array_random(array_keys($cats, $most_cat_count));

        $brands = $this->userRepository->brands($user_id, $user);
        $most_brand_count = max($brands);
        $most_brand = array_random(array_keys($brands, $most_brand_count));

        return compact('user', 'reviews', 'cats', 'most_cat', 'most_cat_count', 'most_brand', 'most_brand_count');
    }

    /**
     * 修改头像（pc+微信）
     */
    public function avatar(Request $request, User $user)
    {
        $request->validate(['file' => 'required|image|max:5120|dimensions:max_width=500,max_height=500']);

        $this->authorize('update', $user);

        //获取文件--存储文件到avatars目录下--再转化为绝对路径
        $path = Storage::url($request->file->store('avatars'));
        $user->update(['avatar' => $path]);
        return ['path' => $path];
    }

    public function api_avatar(Request $request, string $openid)
    {
        $request->validate(['file' => 'required|image|max:5120']);

        $user = $this->userRepository->get_user($openid);
        if ($user) {
            /*$path = Storage::url($request->file->store('avatars'));
            $user->update(['avatar' => $path]);*/

            $img = $request->file;
            $path = $img->hashName('avatars');
            //图片处理---变成450，改成jpg格式
            $handled_img = Image::make($img)->fit(450, 450, function ($constraint) {
                $constraint->upsize();
            })->encode('jpg');

            Storage::put($path, $handled_img);

            $path=Storage::url($path);
            $user->update(['avatar' => $path]);
            return compact('path');
        }
    }

    /**
     * 修改昵称（pc+微信）
     */
    public function name_update(Request $request, User $user)
    {
        $request->validate(['name' => 'required|string|max:16']);

        $this->authorize('update', $user);

        $user->update(['name' => $request->name]);
//        return ['aa'=>'ok'];
    }

    public function api_name_update(Request $request, string $openid)
    {
        $request->validate(['name' => 'required|string|max:16']);

        $user = $this->userRepository->get_user($openid);
        if ($user) $user->update(['name' => $request->name]);
    }

    /**
     * 修改肤质（pc+微信）
     */
    public function skin_update(Request $request, User $user)
    {
        $request->validate(['skin' => 'required|in:0,1,2,3,4,5|integer']);

        $this->authorize('update', $user);

        $user->update(['skin' => $request->skin]);
    }

    public function api_skin_update(Request $request, string $openid)
    {
        $request->validate(['skin' => 'required|in:0,1,2,3,4,5|integer']);

        $user = $this->userRepository->get_user($openid);
        if ($user) $user->update(['skin' => $request->skin]);

    }

}
