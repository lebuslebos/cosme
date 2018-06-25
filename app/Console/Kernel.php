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

            //获取（登录+游客）的进账点评，reviews_count写入数据库
            if (Cache::get('r-p-ids')) {
                $r_p_ids = array_unique(Cache::get('r-p-ids'));
                foreach ($r_p_ids as $r_p_id) {

                    DB::table('products')->where('id', $r_p_id)
                        ->update(['reviews_count' => Cache::get('r-' . $r_p_id . '-p')]);
                    //if (Cache::has('sh-' . $r_p_id)) Cache::forget('sh-' . $r_p_id);//刷新购入场所分布的缓存
                }
            }


            //获取（登录+游客）的进账回购点评，buys_count写入数据库
            if (Cache::get('b-p-ids')) {
                $b_p_ids = array_unique(Cache::get('b-p-ids'));
                foreach ($b_p_ids as $b_p_id) {

                    DB::table('products')->where('id', $b_p_id)
                        ->update(['buys_count' => Cache::get('b-' . $b_p_id . '-p')]);
                }
            }


            //获取（登录）进账的有内容的点评ids，循环的重新计算评分，并存入缓存新的值
            if (Cache::get('p-ids')) {

                $p_ids = array_unique(Cache::get('p-ids'));
                foreach ($p_ids as $p_id) {
                    Cache::forever('ra-' . $p_id, round(DB::table('reviews')
                        ->where([['body', '<>', ''], ['product_id', $p_id]])
                        ->avg('rate'), 1));
                    //if (Cache::has('sk-' . $p_id)) Cache::forget('sk-' . $p_id);//刷新肤质分布的缓存
                }
            }

            Cache::tags(['ranking', 'match'])->flush();//清空排行榜以及匹配用户的缓存


        })->dailyAt('15:00')
            ->after(function () {
                if (Cache::has('r-p-ids')) Cache::forget('r-p-ids');//把点评入账数组归零
                if (Cache::has('b-p-ids')) Cache::forget('b-p-ids');//把(回购)点评入账数组归零
                if (Cache::has('p-ids')) Cache::forget('p-ids');//把(有内容)点评入账数组归零

                //把热门分类的首页排行榜+热门分类的商品页排行榜重新放入缓存
                $popular_cats = [1, 2, 3, 4, 5];
                foreach ($popular_cats as $popular_cat) {
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
