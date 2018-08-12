<?php

namespace App\Observers;

use App\Cat;
use Illuminate\Support\Facades\Cache;

/**
 * Class CatObservers
 *
 * @package \App\Observers
 */
class CatObservers
{
    public function updated(Cat $cat)
    {
        if (Cache::has('cats-' . $cat->id)) Cache::forget('cats-' . $cat->id);//更新分类缓存
    }

    public function saved(Cat $cat)
    {
        if (Cache::has('all-cats')) Cache::forget('all-cats');//更新全部分类缓存
//        if (Cache::has('group-cats')) Cache::forget('group-cats');//更新按大分类排的全部分类缓存
    }

    public function deleted(Cat $cat)
    {
        if (Cache::has('cats-' . $cat->id)) Cache::forget('cats-' . $cat->id);//更新分类缓存
        if (Cache::has('all-cats')) Cache::forget('all-cats');//更新全部分类缓存
//        if (Cache::has('group-cats')) Cache::forget('group-cats');//更新按大分类排的全部分类缓存

    }
}
