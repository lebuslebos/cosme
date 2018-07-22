<?php

namespace App\Observers;

use App\Repositories\ReviewRepository;
use App\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class ReviewObservers
 *
 * @package \App\Observers
 */
class ReviewObservers
{

    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    //游客点评+登录用户新建点评（2种）
    public function created(Review $review)
    {
        $product_id = $review->product_id;
        $brand_id = $review->brand_id;

        Cache::forget('sh-' . $product_id);//刷新购入场所分布的缓存

        //商品和品牌的点评数+1-->并各做持久化处理
        /*Cache::increment('r-' . $review->brand_id . '-b');
        $r_b_ids=Cache::get('r-b-ids',[]);
        $r_b_ids[]=$review->brand_id;
        Cache::forever('r-b-ids',$r_b_ids);

        Cache::increment('r-' . $review->product_id . '-p');
        $r_p_ids=Cache::get('r-p-ids',[]);
        $r_p_ids[]=$review->product_id;
        Cache::forever('r-p-ids',$r_p_ids);*/

        //商品和品牌的点评数,回购数 +1--直接查询数据库
        if ($review->buy == 0) {
            DB::table('products')->where('id', $product_id)->update([
                'reviews_count' => DB::raw('reviews_count + 1'),
                'buys_count' => DB::raw('buys_count + 1'),
            ]);
            DB::table('brands')->where('id', $brand_id)->update([
                'reviews_count' => DB::raw('reviews_count + 1'),
                'buys_count' => DB::raw('buys_count + 1'),
            ]);
        } else {
            DB::table('products')->where('id', $product_id)->increment('reviews_count');
            DB::table('brands')->where('id', $brand_id)->increment('reviews_count');
        }

        /*if ($review->buy == 0) {
            //商品和品牌的回购数+1-->并各做持久化处理
            Cache::increment('b-' . $review->brand_id . '-b');
            $b_b_ids = Cache::get('b-b-ids', []);
            $b_b_ids[] = $review->brand_id;
            Cache::forever('b-b-ids', $b_b_ids);

            Cache::increment('b-' . $review->product_id . '-p');
            $b_p_ids = Cache::get('b-p-ids', []);
            $b_p_ids[] = $review->product_id;
            Cache::forever('b-p-ids', $b_p_ids);

        }*/

        //登录用户新建点评时
        if ($user_id = $review->user_id) {

            Cache::forget('sk-' . $product_id);//刷新肤质分布的缓存

            //用户点评数,回购数+1 --直接查询数据库
            if ($review->buy == 0) {
                DB::table('users')->where('id', $user_id)->update([
                    'reviews_count' => DB::raw('reviews_count + 1'),
                    'buys_count' => DB::raw('buys_count + 1'),
                ]);
            } else {
                DB::table('users')->where('id', $user_id)->increment('reviews_count');
            }

            //用户点评数+1-->并各做持久化处理
            /*Cache::increment('r-' . $review->user_id . '-u');
            $r_u_ids = Cache::get('r-u-ids', []);
            $r_u_ids[] = $review->user_id;
            Cache::forever('r-u-ids', $r_u_ids);
            //用户回购数+1-->并各做持久化处理
            if ($review->buy == 0) {
                Cache::increment('b-' . $review->user_id . '-u');
                $b_u_ids = Cache::get('b-u-ids', []);
                $b_u_ids[] = $review->user_id;
                Cache::forever('b-u-ids', $b_u_ids);
            }*/

            if ($review->body) {

                //记录下有点评进账的商品
                $p_ids = Cache::get('p-ids', []);
                $p_ids[] = $review->product_id;
                Cache::forever('p-ids', $p_ids);

                //直接覆盖首页点评缓存
                $reviews = $this->reviewRepository->reviews();
                Cache::forever('reviews', $reviews);
            }

            //清空个人页最多分类和品牌的缓存
            Cache::forget('users-' . $user_id . '-b');
            Cache::forget('users-' . $user_id . '-c');
        }
    }

    public function updated(Review $review)
    {
        //直接覆盖首页点评缓存
        $reviews = $this->reviewRepository->reviews();
        Cache::forever('reviews', $reviews);
    }

    //游客的新建+登录用户的新建或修改点评（3种）
    public function saved(Review $review)
    {

        Cache::tags('products-' . $review->product_id . '-reviews')->flush();//清空商品页所有点评的缓存

        //登录用户新建+修改点评时
        if ($user_id=$review->user_id) {
            //user-product--我的点评缓存起来(问题待解决：存入全部review进缓存，没办法选单独列进去，因only方法返回的是数组)
            Cache::forever($user_id . '-' . $review->product_id, $review);

            Cache::tags('users-' . $user_id . '-reviews')->flush();//清空个人页所有点评的缓存

        }
    }

    //登录用户删除点评
    public function deleted(Review $review)
    {
        $product_id = $review->product_id;
        $brand_id = $review->brand_id;
        $user_id=$review->user_id;

        Cache::forget('sh-' . $product_id);//刷新购入场所分布的缓存
        Cache::forget('sk-' . $product_id);//刷新肤质分布的缓存

        //商品和品牌的点评数,回购数-1 ; 用户点评数,回购数-1 --直接查询数据库
        if ($review->buy == 0) {
            DB::table('products')->where('id', $product_id)->update([
                'reviews_count' => DB::raw('reviews_count - 1'),
                'buys_count' => DB::raw('buys_count - 1'),
            ]);
            DB::table('brands')->where('id', $brand_id)->update([
                'reviews_count' => DB::raw('reviews_count - 1'),
                'buys_count' => DB::raw('buys_count - 1'),
            ]);
            DB::table('users')->where('id', $user_id)->update([
                'reviews_count' => DB::raw('reviews_count - 1'),
                'buys_count' => DB::raw('buys_count - 1'),
            ]);
        } else {
            DB::table('products')->where('id', $product_id)->decrement('reviews_count');
            DB::table('brands')->where('id', $brand_id)->decrement('reviews_count');
            DB::table('users')->where('id', $user_id)->decrement('reviews_count');
        }

        //用户点评数-1-->并各做持久化处理
        /*Cache::decrement('r-' . $review->user_id . '-u');
        $r_u_ids = Cache::get('r-u-ids', []);
        $r_u_ids[] = $review->user_id;
        Cache::forever('r-u-ids', $r_u_ids);
        //用户回购数-1-->并各做持久化处理
        if ($review->buy == 0) {
            Cache::decrement('b-' . $review->user_id . '-u');
            $b_u_ids = Cache::get('b-u-ids', []);
            $b_u_ids[] = $review->user_id;
            Cache::forever('b-u-ids', $b_u_ids);
        }*/

        if ($review->body) {

            //记录下有点评进账的商品
            $p_ids = Cache::get('p-ids', []);
            $p_ids[] = $review->product_id;
            Cache::forever('p-ids', $p_ids);

            //直接覆盖首页点评缓存
            $reviews = $this->reviewRepository->reviews();
            Cache::forever('reviews', $reviews);
        }

        //清空个人页最多分类和品牌的缓存
        Cache::forget('users-' . $review->user_id . '-b');
        Cache::forget('users-' . $review->user_id . '-c');

        Cache::tags('products-' . $review->product_id . '-reviews')->flush();//清空商品页所有点评的缓存
        Cache::tags('users-' . $review->user_id . '-reviews')->flush();//清空个人页所有点评的缓存

        //user-product--我的点评删除
        Cache::forget($review->user_id . '-' . $review->product_id);
    }
}
