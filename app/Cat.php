<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Cat extends Model
{
    //搜索trait
    use Searchable;

    //这些属性不能被批量赋值
    protected $guarded = [];

    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['name','similar_name','id']);
    }

    /*public function setImgAttribute($value)
    {
        $this->attributes['img'] = config('app.url').'/cats/'.$this->id.'.png';
    }*/

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'reviews');
    }
}
