<?php

namespace App\Repositories;

use App\Cat;
use Illuminate\Support\Facades\Cache;

/**
 * Class CatRepository
 *
 * @package \App\Repositories
 */
class CatRepository
{
    public function all_cats()
    {
        return Cache::rememberForever('all-cats',function (){
            return Cat::select('id','name')->orderBy('id','asc')->get();
        });
    }

    public function popular_cats()
    {
        return Cache::rememberForever('popular-cats', function () {
            return Cat::select('id', 'name')->whereIn('id', config('common.popular_cats'))->orderBy('id')->get();
        });
    }

    public function cat(int $cat_id)
    {
        return Cache::rememberForever('cats-' . $cat_id, function () use ($cat_id) {
            return Cat::find($cat_id,['id','name','similar_name']);
        });
    }

    public function products(int $cat_id,Cat $cat)
    {
        return Cache::tags(['c-products','cats-' . $cat_id . '-products'])
            ->rememberForever('cats-' . $cat_id . '-products-' . request('page', 1), function () use ($cat) {
                return $cat->products()
                    ->select('id','brand_id','name','nick_name','rate','reviews_count','buys_count')
                    ->with(['brand:id,name,common_name','prices'])
                    ->orderBy('reviews_count','desc') //此处的数字是数据库中的旧数据，页面上显示的是最新缓存数据，因此页面上排名可能不是按最新排的
                    ->orderBy('id','asc')
                    ->paginate(config('common.pre_page'));
            });
    }
}
