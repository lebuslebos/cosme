<?php

namespace App\Providers;

use App\Brand;
use App\Cat;
use App\Observers\BrandObservers;
use App\Observers\CatObservers;
use App\Observers\ProductObservers;
use App\Observers\ReviewObservers;
use App\Observers\UserObservers;
use App\Product;
use App\Review;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Facades\Agent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //系统时区
        Carbon::setLocale('zh');

        //视图共享数据

        View::share('is_mobile', Agent::isMobile());
        View::share('is_phone', Agent::isPhone());
        View::share('is_tablet', Agent::isTablet());

        $all_cats =Cache::rememberForever('all-cats',function (){
            return Cat::select('id','name')->orderBy('id','asc')->get();
        });

        view()->composer(['home', 'cats.show', 'users.show'], function ($view) use ($all_cats) {
            $view->with(compact('all_cats'));
        });

        //注册观察者(cat,brand,product主要为后台更新时触发)
        Cat::observe(CatObservers::class);
        Brand::observe(BrandObservers::class);
        Product::observe(ProductObservers::class);

        Review::observe(ReviewObservers::class);
        User::observe(UserObservers::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
