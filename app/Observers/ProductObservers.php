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
        //Cache::increment('p-'.$product->brand_id.'-b');//递增品牌的商品数
    }
    //has_login_review字段被更新时，刷新商品本身的缓存
    public function updated(Product $product)
    {
        if(Cache::has('products-' . $product->id)) Cache::forget('products-' . $product->id);//更新商品缓存
    }

    public function saved(Product $product)
    {
        Cache::tags('cats-'.$product->cat_id.'-products')->flush();//更新cat下的商品缓存
        Cache::tags('brands-'.$product->brand_id.'-products')->flush();//更新brand下的商品缓存
    }
    public function deleted(Product $product)
    {
        //Cache::decrement('p-'.$product->brand_id.'-b');//递减品牌的商品数

        if (Cache::has('products-' . $product->id)) Cache::forget('products-' . $product->id);//更新商品缓存
        Cache::tags('cats-'.$product->cat_id.'-products')->flush();//更新cat下的商品缓存
        Cache::tags('brands-'.$product->brand_id.'-products')->flush();//更新brand下的商品缓存
    }
}
