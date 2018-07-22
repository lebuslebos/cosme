<?php

/*foreach (Cat::pluck('id')->all() as $id){Cache::tags('cats-'.$id.'-products')->flush();}
foreach (Brand::pluck('id')->all() as $id){Cache::tags('brands-'.$id.'-products')->flush();}
foreach (Product::pluck('id')->all() as $id){Cache::tags('products-'.$id.'-reviews')->flush();}
foreach (User::pluck('id')->all() as $id){Cache::tags('users-'.$id.'-reviews')->flush();}
Cache::forget('reviews');*/


/*foreach (User::find(1)->products->pluck('id')->all() as $p_id) {
    $rate = round(DB::table('reviews')->where([['body', '<>', ''], ['product_id', $p_id]])->avg('rate'), 1);
    Cache::forever('ra-' . $p_id,$rate);
    DB::table('products')->where('id', $p_id)->update(['rate' => $rate]);
}*/
