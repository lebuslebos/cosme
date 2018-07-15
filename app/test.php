<?php

$cat_ids=Cat::pluck('id')->all();
foreach ($cat_ids as $cat_id){Cache::tags('cats-' . $cat_id . '-products')->flush();}

$brand_ids=Brand::pluck('id')->all();
foreach ($brand_ids as $brand_id){Cache::tags('brands-' . $brand_id . '-products')->flush();}

Cache::tags('users-2-reviews')->flush();

$product_ids=Product::pluck('id')->all();
foreach ($product_ids as $product_id){Cache::tags('products-' . $product_id . '-reviews')->flush();}
