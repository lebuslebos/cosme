<?php

namespace App\Observers;
use App\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProductObservers
 *
 * @package \App\Observers
 */
class ProductObservers
{

    public function created(Product $product)
    {
        Cache::forget('p-count');//清空全部商品数的缓存
    }
    //has_login_review字段被更新时，刷新商品本身的缓存
    public function updated(Product $product)
    {
        Cache::forget('products-' . $product->id);//更新商品缓存
    }

    public function saved(Product $product)
    {
        Cache::tags('cats-'.$product->cat_id.'-products')->flush();//更新cat下的商品缓存
        Cache::tags('brands-'.$product->brand_id.'-products')->flush();//更新brand下的商品缓存
    }
    public function deleted(Product $product)
    {
        Cache::forget('p-count');//清空全部商品数的缓存

        if (Cache::has('products-' . $product->id)) Cache::forget('products-' . $product->id);//更新商品缓存
        Cache::tags('cats-'.$product->cat_id.'-products')->flush();//更新cat下的商品缓存
        Cache::tags('brands-'.$product->brand_id.'-products')->flush();//更新brand下的商品缓存
    }
}
