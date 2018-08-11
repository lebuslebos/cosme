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
        return Cache::tags(['p-reviews', 'products-' . $product_id . '-reviews'])
            ->rememberForever('products-' . $product_id . '-reviews-' . request('page', 1), function () use ($product) {
                return $product->reviews()
                    ->select('id', 'user_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count', 'hates_count', 'updated_at')
                    ->with('user:id,name,avatar,skin,reviews_count,openid')
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
            $skin_count = array_sum($skins);
            $skin_datas = [];

            for ($i = 0; $i < 6; $i++) {
                $skin_datas[$i] = empty($skins[$i]) ? 0 : ceil(100 * $skins[$i] / $skin_count);
            }
            return $skin_datas;
        });
    }

    public function simple(array $product_ids,string $type)
    {
        $products = Product::select('id', 'name', 'nick_name', 'rate')
            ->whereIn('id', $product_ids)
            ->orderBy('reviews_count', 'desc')->orderBy('buys_count', $type)->orderBy('id', 'desc')->get();

        $products->map(function ($product) {
            $product->star = floor($product->rate);
            $product->halfStar = floor($product->rate) < round($product->rate);
            $product->emptyStar = 7 - round($product->rate);
        });

        return $products;
    }
    public function wx_products($products)
    {
        $products->map(function ($product) {
            $product->star = floor($product->rate);
            $product->halfStar = floor($product->rate) < round($product->rate);
            $product->emptyStar = 7 - round($product->rate);
            $product->buyPercent = $product->buys_count == 0 ? 0 : round(100 * $product->buys_count / $product->reviews_count);
            $product->review = $product->reviews[0];
            unset($product->reviews);
            $product->review->imgs = json_decode($product->review->imgs);
            $product->review->buy = config('common.buys')[$product->review->buy];
            $product->review->shop = config('common.shops')[$product->review->shop];
            $product->review->solar = config('common.solars')[$product->review->updated_at['solar'] - 1];
        });
    }

    public function negative_products()
    {
        return Cache::tags('negative')->rememberForever('negative-products', function () {

            $products = Product::select('id', 'brand_id', 'name', 'nick_name', 'rate', 'reviews_count', 'buys_count')
                ->whereIn('id', config('common.negative_products'))
                ->with([
                    'brand:id,name',
                    'reviews' => function ($query) {
                        $query->select('id', 'user_id', 'product_id', 'rate', 'buy', 'shop', 'likes_count', 'hates_count', 'body', 'imgs', 'updated_at')
                            ->where('rate', '<', 4)->where('buy', 1)->where('body', '<>', '')->latest();
                    },
                    'reviews.user:id,name,avatar,skin,reviews_count,openid'
                ])->orderBy('reviews_count','desc')->orderBy('buys_count','asc')->orderBy('id','desc')->get();

            $this->wx_products($products);

            return $products;
        });
    }

    public function recent_products()
    {
        return Cache::tags('recent')->rememberForever('recent-products', function () {

            $products = Product::select('id', 'brand_id', 'name', 'nick_name', 'rate', 'reviews_count', 'buys_count')
                ->whereIn('id', config('common.recent_products'))
                ->with([
                    'brand:id,name',
                    'reviews' => function ($query) {
                        $query->select('id', 'user_id', 'product_id', 'rate', 'buy', 'shop', 'likes_count', 'hates_count', 'body', 'imgs', 'updated_at')
                            ->where('body', '<>', '')->latest();
                    },
                    'reviews.user:id,name,avatar,skin,reviews_count,openid'
                ])->orderBy('reviews_count','desc')->orderBy('buys_count','desc')->orderBy('id','desc')->get();

            $this->wx_products($products);

            return $products;
        });
    }
}
