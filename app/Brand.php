<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

class Brand extends Model
{
    //搜索trait
    use Searchable;

    //这些属性不能被批量赋值
    protected $guarded = [];
    //取模型时不取这些属性的值
//    protected $hidden = ['similar_name'];--影响搜索，故去掉

    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['name','common_name','country','official_website','similar_name','id']);
    }



    //品牌的商品数，点评数，回购数
    /*public function getProductsCountAttribute()
    {
        return Cache::get('p-' . $this->id . '-b',0);
    }*/
    /*public function getReviewsCountAttribute($value)
    {
        return intval(Cache::rememberForever('r-' . $this->id . '-b',function ()use($value){
            return $value;
        }));
    }
    public function getBuysCountAttribute($value)
    {
        return intval(Cache::rememberForever('b-' . $this->id . '-b',function ()use($value){
            return $value;
        }));
    }*/




    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'reviews');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
