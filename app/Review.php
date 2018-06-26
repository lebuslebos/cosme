<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    //这些属性不能被批量赋值
    protected $guarded = [];
    //取模型时不取这些属性的值
    protected $hidden = ['device'];

    //点评在create,update,delete的时候均触发商品表的updated_at更新
//    protected $touches = ['product'];

    public function getUpdatedAtAttribute($value)
    {
        $dt = Carbon::parse($value);

        switch ($dt->diffInYears()) {
            case 0:
                $year = '今年';
                break;
            case 1:
                $year = '去年';
                break;
            case 2:
                $year = '前年';
                break;
            default:
                $year = $dt->year;
        }
        return ['year' => $year, 'solar' => $dt->month];
    }


    //点评的赞数/踩数---从缓存获取
    public function getLikesCountAttribute($value)
    {
        return Cache::rememberForever('l-' . $this->id, function () use ($value) {
            return $value;
        });
        //return Cache::get('l-' . $this->id,0);
    }

    public function getHatesCountAttribute($value)
    {
        return Cache::rememberForever('h-' . $this->id, function () use ($value) {
            return $value;
        });
        //return Cache::get('h-' . $this->id,0);
    }


    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => '游客',
            'avatar' => Storage::url('avatars/default.jpg'),
            'skin' => 2
        ]);
    }

    /*public function like_users()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function hate_users()
    {
        return $this->belongsToMany(User::class, 'hates');
    }*/


}
