<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    //这些属性不能被批量赋值
    protected $guarded = [];
    //取模型时不取这些属性的值
    protected $hidden = [
        'mobile', 'province','city','remember_token', 'created_at', 'updated_at'
    ];

    public function getSkinAttribute($value)
    {
        $skin = ['中性', '干性', '混合性', '油性', '敏感性', '过敏性'];
        return $skin[intval($value)];
    }


    //用户的点评数，以及其中的回购数
    /*public function getReviewsCountAttribute($value)
    {
        return intval(Cache::rememberForever('r-' . $this->id . '-u',function ()use($value){
            return $value;
        }));
    }

    public function getBuysCountAttribute($value)
    {
        return intval(Cache::rememberForever('b-' . $this->id . '-u',function ()use($value){
            return $value;
        }));
    }*/


    //用户获得的赞数/踩数---从缓存获取（踩数暂不做前台展示）
    public function getLikesCountAttribute($value)
    {
        return intval(Cache::rememberForever('l-' . $this->id. '-u', function () use ($value) {
            return $value;
        }));
        //return Cache::get('l-' . $this->id . '-u',0);
    }

    public function getHatesCountAttribute($value)
    {
        return intval(Cache::rememberForever('h-' . $this->id . '-u', function () use ($value) {
            return $value;
        }));
        //return Cache::get('h-' . $this->id . '-u',0);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'reviews')->withPivot('buy');
    }

    public function cats()
    {
        return $this->belongsToMany(Cat::class, 'reviews')->withPivot('body');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'reviews');
    }


    //判断对某个商品是否已点评
    /*public function is_reviewed($product_id)
    {
        return DB::table('reviews')->where([
                ['user_id', '=', $this->id],
                ['product_id', '=', $product_id]
            ])->count() > 0;
    }*/

    //找出对某个商品的点评--取一个
    public function my_review($product_id)
    {
        //因新建或更新（登录用户）点评时已存入缓存。为防止缓存丢失-->若缓存无数据则仍去查询
         $my_review = Cache::rememberForever($this->id . '-' . $product_id, function () use ($product_id) {
             return Review::select('id','user_id','rate','body','imgs','buy','shop','likes_count','hates_count','updated_at')->where([['user_id', $this->id], ['product_id', $product_id]])->first() ?? '';
         });
         return $my_review;
//        return Cache::get($this->id . '-' . $product_id,'');
    }
}
