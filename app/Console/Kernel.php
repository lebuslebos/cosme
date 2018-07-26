<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {

            //获取被点赞/点踩的进账点评，写入数据库
            if (Cache::get('l-r-ids')) {
                $unique_l_r_ids = array_unique(Cache::pull('l-r-ids'));
                foreach ($unique_l_r_ids as $l_r_id) {
                    DB::table('reviews')->where('id', $l_r_id)
                        ->update(['likes_count' => Cache::get('l-' . $l_r_id)]);
                }
            }
            if (Cache::get('h-r-ids')) {
                $unique_h_r_ids = array_unique(Cache::pull('h-r-ids'));
                foreach ($unique_h_r_ids as $h_r_id) {
                    DB::table('reviews')->where('id', $h_r_id)
                        ->update(['hates_count' => Cache::get('h-' . $h_r_id)]);
                }
            }

            //获取被点赞/点踩的进账用户，写入数据库
            if (Cache::get('l-u-ids')) {
                $unique_l_u_ids = array_unique(Cache::pull('l-u-ids'));
                foreach ($unique_l_u_ids as $l_u_id) {
                    DB::table('users')->where('id', $l_u_id)
                        ->update(['likes_count' => Cache::get('l-' . $l_u_id . '-u')]);
                }
            }
            if (Cache::get('h-u-ids')) {
                $unique_h_u_ids = array_unique(Cache::pull('h-u-ids'));
                foreach ($unique_h_u_ids as $h_u_id) {
                    DB::table('users')->where('id', $h_u_id)
                        ->update(['hates_count' => Cache::get('h-' . $h_u_id, '-u')]);
                }
            }


            //获取（登录+游客）的进账点评，reviews_count写入数据库(品牌+商品+用户)
            /*if (Cache::get('r-b-ids')) {
                $unique_r_b_ids = array_unique(Cache::pull('r-b-ids'));
                foreach ($unique_r_b_ids as $r_b_id) {
                    DB::table('brands')->where('id', $r_b_id)
                        ->update(['reviews_count' => Cache::get('r-' . $r_b_id . '-b')]);
                }
            }
            if (Cache::get('r-p-ids')) {
                $unique_r_p_ids = array_unique(Cache::pull('r-p-ids'));
                foreach ($unique_r_p_ids as $r_p_id) {
                    DB::table('products')->where('id', $r_p_id)
                        ->update(['reviews_count' => Cache::get('r-' . $r_p_id . '-p')]);
                }
            }
            if (Cache::get('r-u-ids')) {
                $unique_r_u_ids = array_unique(Cache::pull('r-u-ids'));
                foreach ($unique_r_u_ids as $r_u_id) {
                    DB::table('users')->where('id', $r_u_id)
                        ->update(['reviews_count' => Cache::get('r-' . $r_u_id . '-u')]);
                }
            }*/

            //获取（登录+游客）的进账回购点评，buys_count写入数据库(品牌+商品+用户)
            /*if (Cache::get('b-b-ids')) {
                $unique_b_b_ids = array_unique(Cache::pull('b-b-ids'));
                foreach ($unique_b_b_ids as $b_b_id) {
                    DB::table('brands')->where('id', $b_b_id)
                        ->update(['buys_count' => Cache::get('b-' . $b_b_id . '-b')]);
                }
            }
            if (Cache::get('b-p-ids')) {
                $unique_b_p_ids = array_unique(Cache::pull('b-p-ids'));
                foreach ($unique_b_p_ids as $b_p_id) {
                    DB::table('products')->where('id', $b_p_id)
                        ->update(['buys_count' => Cache::get('b-' . $b_p_id . '-p')]);
                }
            }
            if (Cache::get('b-u-ids')) {
                $unique_b_u_ids = array_unique(Cache::pull('b-u-ids'));
                foreach ($unique_b_u_ids as $b_u_id) {
                    DB::table('users')->where('id', $b_u_id)
                        ->update(['buys_count' => Cache::get('b-' . $b_u_id . '-u')]);
                }
            }*/

            //获取（登录）进账的有内容的点评ids，循环的重新计算评分，并存入缓存新的值
            if (Cache::get('p-ids')) {

                $unique_p_ids = array_unique(Cache::pull('p-ids'));
                foreach ($unique_p_ids as $p_id) {
                    /*Cache::forever('ra-' . $p_id, round(DB::table('reviews')
                        ->where([['body', '<>', ''], ['product_id', $p_id]])
                        ->avg('rate'), 1));*/
                    //重新计算平均分
                    $rate = round(DB::table('reviews')
                        ->where([['body', '<>', ''], ['product_id', $p_id]])
                        ->avg('rate'), 1);

                    Cache::forever('ra-' . $p_id,$rate);//分数放入缓存，方便页面上的调用（因商品信息都已缓存，唯独rate等需实时）
                    DB::table('products')->where('id', $p_id)->update(['rate' => $rate]);//同步至数据库
                }
            }

            Cache::tags(['ranking', 'match'])->flush();//清空排行榜以及匹配用户的缓存

//            Cache::forget('hot-brands');//清空热门品牌的缓存（微信端)）

            //热门品牌+国家分组品牌放入缓存（微信端）
            $hot_brands=DB::table('brands')->select('id','name')->orderBy('reviews_count','desc')->orderBy('id','asc')->take(8)->get();
            Cache::forever('hot-brands',$hot_brands);

            $country_brands = [];
            foreach (config('common.big_brands') as $big_brand) {
                $country_brands[] = DB::table('brands')->select('id', 'name','country_id')->where('country_id', $big_brand)
                    ->orderBy('reviews_count', 'desc')->orderBy('buys_count', 'desc')->orderBy('id', 'asc')->get();
            }
            $country_brands[] = DB::table('brands')->whereNotIn('country_id',config('common.big_brands'));
            Cache::forever('country-brands',$country_brands);



        })->dailyAt('3:00')
            ->after(function () {

                //把热门分类的首页排行榜+热门分类的商品页排行榜重新放入缓存
                foreach (config('common.popular_cats') as $popular_cat) {
                    Artisan::call('ranking:cache', ['cat' => $popular_cat]);
                }


            });

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
