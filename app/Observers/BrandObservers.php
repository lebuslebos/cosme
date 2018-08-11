<?php

namespace App\Observers;
use App\Brand;
use Illuminate\Support\Facades\Cache;

/**
 * Class BrandObservers
 *
 * @package \App\Observers
 */
class BrandObservers
{
    public function created(Brand $brand)
    {
        Cache::forget('b-count');//清空全部商品数的缓存
    }
    public function updated(Brand $brand)
    {
        Cache::forget('brands-' . $brand->id);//更新品牌缓存
    }

    public function saved(Brand $brand)
    {
//        if (Cache::has('all-brands')) Cache::forget('all-brands');//更新全部品牌缓存


//！！！因已加入定时计划，若后台增加或更改品牌名时需手动更新！！Cache::forget('country-brands');//更新按国家分类的全部品牌缓存


    }

    public function deleted(Brand $brand)
    {
        Cache::forget('b-count');//清空全部商品数的缓存

        if (Cache::has('brands-' . $brand->id)) Cache::forget('brands-' . $brand->id);//更新品牌缓存
//        if (Cache::has('all-brands')) Cache::forget('all-brands');//更新全部品牌缓存
        Cache::forget('country-brands');//更新按国家分类的全部品牌缓存
    }
}
