<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

class Product extends Model
{
    //搜索trait
    use Searchable;

    //这些属性不能被批量赋值
    protected $guarded = [];

    protected $casts = [
        'has_login_review' => 'boolean',
    ];

    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['name', 'common_name', 'nick_name', 'id']);

        $brand = $this->brand;
        $arr['brand'] = $brand->name . ',' . $brand->common_name . ',' . $brand->country . ',' . $brand->official_website . ',' . $brand->similar_name;

        $cat = $this->cat;
        $arr['cat'] = $cat->name . ',' . $cat->similar_name;

        return $arr;
    }


    //商品的评分，点评数，以及其中的回购数
    public function getRateAttribute()
    {
        return Cache::get('ra-' . $this->id, 4.0);
    }

    public function getReviewsCountAttribute()
    {
        return Cache::get('r-' . $this->id . '-p', 0);
    }

    public function getBuysCountAttribute()
    {
        return Cache::get('b-' . $this->id . '-p', 0);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function colors()
    {
        return $this->hasMany(Color::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reviews')->withPivot('buy');
    }


    //点评关联
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /* public function cacheKey()
     {
         return sprintf(
             "%s/%s-%s-%s",
             $this->getTable(),
             $this->getKey(),
             $this->updated_at->timestamp,
             request('page', 1)
         );
     }*/

    //缓存商品
    /*public function getCachedProductsAttribute()
    {
        $product = Cache::tags('products-' . $this->getKey())
            ->rememberForever('products-' . $this->getKey(), function () {
                return $this->load(['brand:id,name,common_name', 'cat:id,name', 'prices', 'colors']);
            });
        return $product;
    }*/

    //缓存点评
    /* public function getCachedReviewsAttribute()
     {
         $reviews = Cache::tags('products-' . $this->getKey() . '-reviews')
             ->rememberForever('products-' . $this->getKey() . '-reviews-'.request('page', 1), function () {
                 return $this->reviews()
                     ->with('user:id,name,avatar,skin,reviews_count')
                     ->latest('updated_at')
                     ->paginate(3);
             });
         return $reviews;
     }*/


}
