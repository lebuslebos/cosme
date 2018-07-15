<?php

namespace App\Repositories;

use App\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProductRepository
 *
 * @package \App\Repositories
 */
class ProductRepository
{
    public function product(int $product_id)
    {
        return Cache::rememberForever('products-' . $product_id, function () use ($product_id) {
            return Product::find($product_id, ['id', 'cat_id', 'brand_id', 'name', 'nick_name', 'rate', 'reviews_count', 'buys_count', 'has_login_review'])
                ->load(['brand:id,name,common_name', 'cat:id,name', 'prices', 'colors']);
        });
    }

    public function reviews(int $product_id, Product $product)
    {
        return Cache::tags('products-' . $product_id . '-reviews')
            ->rememberForever('products-' . $product_id . '-reviews-' . request('page', 1), function () use ($product) {
                return $product->reviews()
                    ->select('id', 'user_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count', 'hates_count', 'updated_at')
                    ->with('user:id,name,avatar,skin,reviews_count')
                    ->orderByRaw('if(body="",0,1) DESC')
                    ->latest('updated_at')
                    ->orderBy('id', 'desc')
                    ->paginate(config('common.pre_page'));
            });
    }

    public function shop_datas(int $product_id, Product $product)
    {
        return Cache::rememberForever('sh-' . $product_id, function () use ($product) {

            $shops = array_count_values($product->reviews()->pluck('shop')->all());
            $shop_datas = [];
            for ($i = 0; $i < 5; $i++) {
                $shop_datas[$i] = empty($shops[$i]) ? 0 : ceil(100 * $shops[$i] / $product->reviews_count);
            }
            return $shop_datas;
        });
    }

    public function skin_datas(int $product_id, Product $product)
    {
        return Cache::rememberForever('sk-' . $product_id, function () use ($product) {

            $skins = array_count_values($product->users()->pluck('users.skin')->all());
            $skin_datas = [];

            for ($i = 0; $i < 6; $i++) {
                $skin_datas[$i] = empty($skins[$i]) ? 0 : ceil(100*$skins[$i]/$product->reviews_count);
            }
            return $skin_datas;
        });
    }
}
