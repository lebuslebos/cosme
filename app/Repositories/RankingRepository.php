<?php

namespace App\Repositories;

use App\Brand;
use App\Cat;
use App\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Class RankingRepository
 *
 * @package \App\Repositories
 */
class RankingRepository
{
    //根据分类排名
    public function ranking_by_cat(int $cat_id, string $type, int $limit = 5)
    {
        $products = Product::select('id', 'brand_id', 'name', 'rate', 'reviews_count', 'buys_count')
            ->where([['cat_id', $cat_id], ['reviews_count', '>', 10]])//点评数大于10
            ->with('brand:id,name')//预加载brand
            ->orderByRaw('buys_count/reviews_count ' . $type)//按回购数倒序or顺序排
            ->orderBy('reviews_count',$type)
            ->limit($limit)//取几个，默认5个
            ->get();

        return $products;
    }

    //tags--ranking(便于定期清除所有ranking缓存),key命名例：cats-1-desc-10(1为catid,10为取几个商品)
    public function cached_ranking_by_cat(int $cat_id, string $type, int $limit = 5)
    {
        $products = Cache::tags('ranking')
            ->rememberForever('cats-' . $cat_id . '-' . $type . '-' . $limit, function () use ($cat_id, $type, $limit) {
                return $this->ranking_by_cat($cat_id, $type, $limit);//排行榜
            });

        return $products;
    }


    //根据品牌排名
    public function ranking_by_brand(int $brand_id, string $type, int $limit = 5)
    {
        $products = Product::select('id', 'cat_id', 'name', 'rate', 'reviews_count', 'buys_count')
            ->where([['brand_id', $brand_id], ['reviews_count', '>', 10]])
            ->with('cat:id,name')
            ->orderByRaw('buys_count/reviews_count ' . $type)
            ->orderBy('reviews_count',$type)
            ->limit($limit)
            ->get();
        return $products;
    }

    //tags--ranking(便于定期清除所有ranking缓存),key命名例：brands-1-desc-10(1为catid,10为取几个商品)
    public function cached_ranking_by_brand(int $brand_id, string $type, int $limit = 5)
    {
        $products = Cache::tags('ranking')
            ->rememberForever('brands-' . $brand_id . '-' . $type . '-' . $limit, function () use ($brand_id, $type, $limit) {
                return $this->ranking_by_brand($brand_id, $type, $limit);//排行榜
            });

        return $products;
    }
}
