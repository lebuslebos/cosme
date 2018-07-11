<?php

namespace App\Observers;

use App\Review;
use Illuminate\Support\Facades\Cache;

/**
 * Class ReviewObservers
 *
 * @package \App\Observers
 */
class ReviewObservers
{
    //游客点评+登录用户新建点评（2种）
    public function created(Review $review)
    {
        //刷新购入场所分布的缓存
        if (Cache::has('sh-' . $review->product_id )) Cache::forget('sh-' . $review->product_id );

        //商品和品牌的点评数+1-->并各做持久化处理
        Cache::increment('r-' . $review->brand_id . '-b');
        $r_b_ids=Cache::get('r-b-ids',[]);
        $r_b_ids[]=$review->brand_id;
        Cache::forever('r-b-ids',$r_b_ids);

        Cache::increment('r-' . $review->product_id . '-p');
        $r_p_ids=Cache::get('r-p-ids',[]);
        $r_p_ids[]=$review->product_id;
        Cache::forever('r-p-ids',$r_p_ids);



        if ($review->buy == 0) {
            //商品和品牌的回购数+1-->并各做持久化处理
            Cache::increment('b-' . $review->brand_id . '-b');
            $b_b_ids=Cache::get('b-b-ids',[]);
            $b_b_ids[]=$review->brand_id;
            Cache::forever('b-b-ids',$b_b_ids);

            Cache::increment('b-' . $review->product_id . '-p');
            $b_p_ids=Cache::get('b-p-ids',[]);
            $b_p_ids[]=$review->product_id;
            Cache::forever('b-p-ids',$b_p_ids);

        }


        //登录用户新建点评时
        if ($review->user_id) {
            //刷新肤质分布的缓存
            if (Cache::has('sk-' . $review->product_id)) Cache::forget('sk-' . $review->product_id);

            //用户点评数+1-->并各做持久化处理
            Cache::increment('r-' . $review->user_id . '-u');
            $r_u_ids=Cache::get('r-u-ids',[]);
            $r_u_ids[]=$review->user_id;
            Cache::forever('r-u-ids',$r_u_ids);
            //用户回购数+1-->并各做持久化处理
            if ($review->buy == 0) {
                Cache::increment('b-' . $review->user_id . '-u');
                $b_u_ids=Cache::get('b-u-ids',[]);
                $b_u_ids[]=$review->user_id;
                Cache::forever('b-u-ids',$b_u_ids);
            }


            if ($review->body) {

                //记录下有点评进账的商品
                $p_ids = Cache::get('p-ids',[]);
                $p_ids[]=$review->product_id;
//                array_push($p_ids, $review->product_id);
                Cache::forever('p-ids', $p_ids);

                if (Cache::has('reviews')) Cache::forget('reviews');//清空首页点评缓存
            }


            //清空个人页最多分类和品牌的缓存
            if (Cache::has('users-' . $review->user_id . '-b')) Cache::forget('users-' . $review->user_id . '-b');
            if (Cache::has('users-' . $review->user_id . '-c')) Cache::forget('users-' . $review->user_id . '-c');


        }
    }

    public function updated(Review $review)
    {

    }


    //游客的新建+登录用户的新建或修改点评（3种）
    public function saved(Review $review)
    {
//        var_dump('触发saved事件');

        Cache::tags('products-' . $review->product_id . '-reviews')->flush();//清空商品页所有点评的缓存

        //登录用户新建+修改点评时
        if ($review->user_id) {

            //user-product--我的点评缓存起来(问题待解决：存入全部review进缓存，没办法选单独列进去，因only方法返回的是数组)
            Cache::forever($review->user_id . '-' . $review->product_id, $review);

            Cache::tags('users-' . $review->user_id . '-reviews')->flush();//清空个人页所有点评的缓存

        }
    }
}
