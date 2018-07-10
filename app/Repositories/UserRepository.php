<?php

namespace App\Repositories;
use App\User;
use Illuminate\Support\Facades\Cache;

/**
 * Class UserRepository
 *
 * @package \App\Repositories
 */
class UserRepository
{

    public function get_user(string $openid)
    {
        //没有的话return null
        $user=Cache::rememberForever($openid, function () use ($openid) {
            return User::where('openid', $openid)->first();
        });
        return $user;
    }

    //微信-->1，登录时联动用户信息；2，已登录用户展示信息
    public function wx_user(User $user,string $openid)
    {
        if ($user->reviews_count == 0) return compact('user','openid');

        $user_id = $user->id;
        $reviews = Cache::tags('users-' . $user_id . '-reviews')
            ->rememberForever('users-' . $user_id . '-reviews-' . request('page', 1), function () use ($user) {
                return $user->reviews()
                    ->select('id', 'user_id', 'product_id', 'cat_id', 'brand_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count', 'hates_count', 'updated_at')
                    ->with(['cat:id,name', 'brand:id,name,common_name', 'product:id,name,nick_name,rate,reviews_count,buys_count', 'product.prices'])
                    ->latest('updated_at')
                    ->simplePaginate(config('common.pre_page_mobile'));
            });

        $reviews->withPath('users/'.$openid);

        //用户点评最多的品类--cats为映射数组，相同的话随机取一个，并算出占了几个
        $cats = Cache::rememberForever('users-' . $user_id . '-c', function () use ($user) {
            return array_count_values($user->cats()->pluck('cats.name')->all());
        });
        //先取最大数，再取出key，key即为catname
        $most_cat_count = max($cats);
        $most_cat = array_random(array_keys($cats, $most_cat_count));

        //用户点评最多的品牌--brands为映射数组，相同的话随机取一个，并算出占了几个
        $brands = Cache::rememberForever('users-' . $user_id . '-b', function () use ($user) {
            return array_count_values($user->brands()->pluck('brands.name')->all());
        });
        $most_brand_count = max($brands);
        $most_brand = array_random(array_keys($brands, $most_brand_count));


        //如果用户登录，且有修改权限（即登录用户看的是自己的个人页面），且至少有1个回购点评
        if ($user->buys_count > 0) {

            $matches = Cache::tags('match-' . $user->id)->rememberForever('match-map-' . $user->id, function () use ($user) {

                $buy_products = $user->products()->where('buy', 0)->pluck('products.id')->all();//当前用户会回购的商品ids

                //映射数组
                return array_count_values(Review::where([['buy', 0], ['user_id', '<>', null], ['user_id', '<>', $user->id]])
                    ->whereIn('product_id', $buy_products)
                    ->pluck('user_id')->all());

            });

            if (filled($matches)) {

                $match_count = max($matches);//匹配到的最大次数

                $match_user = Cache::tags('match-' . $user->id)->rememberForever('match-user-' . $user->id, function () use ($matches, $match_count) {
                    $match_id = array_random(array_keys($matches, $match_count));//选其中一个匹配用户
                    return User::find($match_id, ['id', 'name', 'avatar', 'skin']);
                });
            }

        }
        return compact('user', 'openid','reviews', 'cats', 'most_cat', 'most_cat_count', 'most_brand', 'most_brand_count', 'match_user', 'match_count');

    }
}
