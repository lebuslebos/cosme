<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class UserObservers
 *
 * @package \App\Observers
 */
class UserObservers
{
    public function updated(User $user)
    {
        //刷新个人页的个人信息的缓存
        if (Cache::has('users-' . $user->id)) Cache::forget('users-' . $user->id);
//        Cache::tags('products-' . $product_id . '-reviews')

        //刷新她点评过的所有商品下面的点评的缓存+肤质分布的缓存
        if ($user->reviews_count > 0) {

            $product_ids = DB::table('reviews')->where('user_id', $user->id)->pluck('product_id')->all();
            foreach ($product_ids as $product_id) {
                Cache::tags('products-' . $product_id . '-reviews')->flush();
                if(Cache::has('sk-'.$product_id))Cache::forget('sk-'.$product_id);
            }
        }


    }

}
