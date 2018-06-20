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

    }

    public function deleted(Brand $brand)
    {
        if (Cache::has('brands-' . $brand->id)) Cache::forget('brands-' . $brand->id);//更新品牌缓存

    }
}
