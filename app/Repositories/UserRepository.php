<?php

namespace App\Repositories;

use App\Review;
use App\User;
use Illuminate\Support\Facades\Cache;

/**
 * Class UserRepository
 *
 * @package \App\Repositories
 */
class UserRepository
{
    public function user(int $user_id)
    {
        return Cache::rememberForever('users-' . $user_id, function () use ($user_id) {
            return User::find($user_id, ['id', 'name', 'avatar', 'skin', 'reviews_count', 'buys_count', 'likes_count', 'hates_count']);
        });
    }
    public function reviews(int $user_id, User $user)
    {
        return Cache::tags('users-' . $user_id . '-reviews')
            ->rememberForever('users-' . $user_id . '-reviews-' . request('page', 1), function () use ($user) {
                return $user->reviews()
                    ->select('id', 'user_id', 'product_id', 'cat_id', 'brand_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count', 'hates_count', 'updated_at')
                    ->with(['cat:id,name', 'brand:id,name,common_name', 'product:id,name,nick_name,rate,reviews_count,buys_count', 'product.prices'])
                    ->latest('updated_at')
                    ->paginate(config('common.pre_page'));
            });
    }

    public function cats(int $user_id, User $user)
    {
        return Cache::rememberForever('users-' . $user_id . '-c', function () use ($user) {
            return array_count_values($user->cats()->pluck('cats.name')->all());
        });
    }

    public function brands(int $user_id, User $user)
    {
        return Cache::rememberForever('users-' . $user_id . '-b', function () use ($user) {
            return array_count_values($user->brands()->pluck('brands.name')->all());
        });
    }

    public function matches(int $user_id, User $user)
    {
        return Cache::tags('match-' . $user_id)->rememberForever('match-map-' . $user_id, function () use ($user, $user_id) {

            $buy_products = $user->products()->where('buy', 0)->pluck('products.id')->all();//当前用户会回购的商品ids

            //映射数组
            return array_count_values(Review::where([['buy', 0], ['user_id', '<>', null], ['user_id', '<>', $user_id]])
                ->whereIn('product_id', $buy_products)
                ->pluck('user_id')->all());

            /*//和当前用户同样会回购同样商品的其他用户
            $match = $user->products()->where('buy', 0)->get()//当前用户会回购的商品
            ->flatMap->users//点评过这些商品的用户们，点评过同一个商品算一次记录
            ->where('id', '<>', $user_id)//去掉当前用户
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
    }

    public function match_user(int $user_id, array $matches, int $match_count)
    {
        return Cache::tags('match-' . $user_id)->rememberForever('match-user-' . $user_id, function () use ($matches, $match_count) {
            $match_id = array_random(array_keys($matches, $match_count));//选其中一个匹配用户
            return User::find($match_id, ['id', 'name', 'avatar', 'skin']);
        });
    }


    public function get_user(string $openid)
    {
        //没有的话return null
        $user = Cache::rememberForever($openid, function () use ($openid) {
            return User::where('openid', $openid)->first();
        });
        return $user;
    }

    //微信-->1，登录时联动用户信息；2，已登录用户展示信息
    public function wx_user(User $user, string $openid)
    {
        if ($user->reviews_count == 0) return compact('user', 'openid');

        $user_id = $user->id;
        $reviews = $this->reviews($user_id, $user);
        $reviews->withPath('users/' . $openid);

        //用户点评最多的品类--cats为映射数组，相同的话随机取一个，并算出占了几个
        $cats = $this->cats($user_id, $user);
        $most_cat_count = max($cats);//先取最大数，再取出key，key即为catname
        $most_cat = array_random(array_keys($cats, $most_cat_count));

        //用户点评最多的品牌--brands为映射数组，相同的话随机取一个，并算出占了几个
        $brands = $this->brands($user_id, $user);
        $most_brand_count = max($brands);
        $most_brand = array_random(array_keys($brands, $most_brand_count));


        //如果用户登录，且有修改权限（即登录用户看的是自己的个人页面），且至少有1个回购点评
        if ($user->buys_count > 0) {

            $matches = $this->matches($user_id,$user);

            if (filled($matches)) {

                $match_count = max($matches);//匹配到的最大次数

                $match_user = $this->match_user($user_id,$matches,$match_count);
            }

        }
        return compact('user', 'openid', 'reviews', 'cats', 'most_cat', 'most_cat_count', 'most_brand', 'most_brand_count', 'match_user', 'match_count');

    }
}
