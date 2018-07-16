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
    public function updated(Brand $brand)
    {
        if (Cache::has('brands-' . $brand->id)) Cache::forget('brands-' . $brand->id);//更新品牌缓存
    }

    public function saved(Brand $brand)
    {
        if (Cache::has('all-brands')) Cache::forget('all-brands');//更新全部品牌缓存
        if (Cache::has('country-brands')) Cache::forget('country-brands');//更新按国家分类的全部品牌缓存
    }

    public function deleted(Brand $brand)
    {
        if (Cache::has('brands-' . $brand->id)) Cache::forget('brands-' . $brand->id);//更新品牌缓存
        if (Cache::has('all-brands')) Cache::forget('all-brands');//更新全部品牌缓存
        if (Cache::has('country-brands')) Cache::forget('country-brands');//更新按国家分类的全部品牌缓存
    }
}
