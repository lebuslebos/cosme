<?php

namespace App\Repositories;

use App\Brand;
use Illuminate\Support\Facades\Cache;

/**
 * Class BrandRepository
 *
 * @package \App\Repositories
 */
class BrandRepository
{

    public function brand(int $brand_id)
    {
        return Cache::rememberForever('brands-' . $brand_id, function () use ($brand_id) {
            return Brand::find($brand_id, ['id', 'name', 'common_name', 'country', 'country_id', 'official_website', 'reviews_count', 'buys_count']);
        });
    }

    public function products(int $brand_id,Brand $brand)
    {
        return Cache::tags('brands-' . $brand_id . '-products')
            ->rememberForever('brands-' . $brand_id . '-products-' . request('page', 1), function () use ($brand) {
                return $brand->products()
                    ->select('id', 'cat_id', 'name', 'nick_name', 'rate', 'reviews_count', 'buys_count')
                    ->with(['cat:id,name', 'prices'])
                    ->orderBy('reviews_count', 'desc')//此处的数字是数据库中的旧数据，页面上显示的是最新缓存数据，因此页面上排名可能不是按最新排的
                    ->orderBy('id', 'asc')
                    ->paginate(config('common.pre_page'));
            });
    }
}
