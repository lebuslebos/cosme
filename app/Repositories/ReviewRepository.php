<?php

namespace App\Repositories;

use App\Review;
use Illuminate\Support\Facades\Cache;

/**
 * Class ReviewRepository
 *
 * @package \App\Repositories
 */
class ReviewRepository
{
    public function index()
    {
        return Cache::rememberForever('reviews', function () {
            return $this->reviews();
        });
    }

    public function reviews()
    {
        return Review::select('id', 'user_id', 'product_id', 'brand_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count', 'hates_count', 'updated_at')
                ->where('body', '<>', '')
                ->with(['product:id,name,rate,reviews_count,buys_count', 'brand:id,name', 'user:id,name,avatar,skin,reviews_count'])
                ->latest()
                ->orderBy('id', 'desc')
                ->take(config('common.pre_page'))
                ->get();
    }
}
