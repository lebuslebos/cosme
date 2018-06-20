<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //这些属性不能被批量赋值
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
