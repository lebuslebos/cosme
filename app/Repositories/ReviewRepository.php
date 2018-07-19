<?php

namespace App\Repositories;

use App\Http\Requests\StoreReviewRequest;
use App\Product;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Facades\Agent;
use Zhuzhichao\IpLocationZh\Ip;

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
            ->with(['product:id,name,rate,reviews_count,buys_count', 'brand:id,name', 'user:id,name,avatar,skin,reviews_count,openid'])
            ->latest()
            ->orderBy('id', 'desc')
            ->take(config('common.pre_page'))
            ->get();
    }

    public function img(Request $request)
    {
        //获取文件--存储文件到reviews/img目录下--再转化为绝对路径
//        $path=Storage::url($request->file->store('reviews/imgs'));
        $request->validate(['file' => 'required|image|max:5120']);

        $img = $request->file;
        $path = $img->hashName('reviews');
        /*$handled_img = Image::make($img)->fit(400, 400, function ($constraint) {
            $constraint->upsize();
        })->encode('jpg');*/
        //图片处理---宽度变成450，自适应高度，改成jpg格式
        $handled_img = Image::make($img)->widen(450, function ($constraint) {
            $constraint->upsize();//防止小图被拉伸
        })->encode('jpg');

        Storage::put($path, $handled_img);

        return ['path' => Storage::url($path)];
    }

    public function store(StoreReviewRequest $request, Product $product, int $user_id)
    {
        if (!$product->has_login_review) $product->update(['has_login_review' => true]);//若此商品之前从未有过登录用户的点评，则把字段改为true

        $review = Review::create([
            'user_id' => $user_id,
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'brand_id' => $product->brand_id,
            'rate' => $request->rate,
            'buy' => $request->buy,
            'shop' => $request->shop,
            'body' => request('body', ''),
            'imgs' => json_encode(request('imgs')),
            'device' => Agent::device(),
            'province' => Ip::find(request()->ip())[1],
            'city' => Ip::find(request()->ip())[2]
        ]);
        return $review;
    }

    public function update(StoreReviewRequest $request, Product $product, Review $review,int $user_id)
    {
        $pre_buy = $review->buy;

        $review->update([
            'rate' => $request->rate,
            'buy' => $request->buy,
            'shop' => $request->shop,
            'body' => request('body', ''),
            'imgs' => json_encode(request('imgs')),
        ]);
        //如果评分变了--且两次至少一次有内容，才重新算一遍----忽略评分没变，内容由空变有/由有变空时总评分也会变的情况，留做下一次新建点评时重新计算
        /*if ($review->rate != $pre_rate && ($pre_body || $review->body)) {
            Cache::forever('ra-' . $product->id, round($product->reviews()
                ->where('body', '<>', '')->avg('rate'), 1));//商品评分更新,并存入缓存
        }*/
        //如果回购变了
        if ($review->buy != $pre_buy) {
            //之前的buy为0的时候，说明现在改成了1（不会回购），把回购数减一。反之一样
            if ($pre_buy == 0) {
                Cache::decrement('b-' . $user_id . '-u');
                Cache::decrement('b-' . $product->id . '-p');
                Cache::decrement('b-' . $product->brand_id . '-b');
            } else {
                Cache::increment('b-' . $user_id . '-u');
                Cache::increment('b-' . $product->id . '-p');
                Cache::increment('b-' . $product->brand_id . '-b');
            }
        }

        return $review;
    }
}
