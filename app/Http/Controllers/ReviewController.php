<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Http\Requests\StoreImgRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Product;
use App\Repositories\CatRepository;
use App\Repositories\RankingRepository;
use App\Repositories\ReviewRepository;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Facades\Agent;
use Zhuzhichao\IpLocationZh\Ip;

class ReviewController extends Controller
{
    protected $rankingRepository;
    protected $reviewRepository;
    protected $catRepository;

    public function __construct(RankingRepository $rankingRepository,ReviewRepository $reviewRepository,CatRepository $catRepository)
    {
        $this->middleware('auth')->except(['index','api_index', 'ranking', 'vote', 'store_visitor']);
        $this->rankingRepository = $rankingRepository;
        $this->reviewRepository=$reviewRepository;
        $this->catRepository=$catRepository;
    }


    //所有用户,点赞--点踩的逻辑
    public function vote(Request $request)
    {
        $request->validate([
            'review' => 'required|integer|min:1',
            'user' => 'required|integer|min:1',
            'type' => ['required', Rule::in(['l', 'h'])],
        ]);

        //点评的获赞数/获踩数+1-->并各做持久化处理
        Cache::increment($request->type . '-' . $request->review);
        $r_ids = Cache::get($request->type . '-r-ids', []);
        $r_ids[] = $request->review;
        Cache::forever($request->type . '-r-ids', $r_ids);

        //用户的获赞数/获踩数+1-->并各做持久化处理
        Cache::increment($request->type . '-' . $request->user . '-u');
        $u_ids = Cache::get($request->type . '-u-ids', []);
        $u_ids[] = $request->user;
        Cache::forever($request->type . '-u-ids', $r_ids);


        //return 'ok';
    }

    public function index()
    {
        $reviews =$this->reviewRepository->index();

        //随机回购ranking,并只选取点评数大于10的进行排行
        $popular_cats = $this->catRepository->popular_cats();

        $red_cat = $popular_cats->random();
        $red_products = $this->rankingRepository->cached_ranking_by_cat($red_cat->id, 'desc');

        $black_cat = $popular_cats->random();
        $black_products = $this->rankingRepository->cached_ranking_by_cat($black_cat->id, 'asc');

        return view('home', compact('reviews', 'red_cat', 'red_products', 'black_cat', 'black_products'));
    }

    public function api_index()
    {
        $reviews = $this->reviewRepository->index();
        return compact('reviews');
    }


    public function ranking(int $cat_id, Request $request)
    {
//        return ['aa'=>$request->type];
        $request->validate(['type' => ['required', Rule::in(['desc', 'asc'])]]);

        return $this->rankingRepository->cached_ranking_by_cat($cat_id, $request->type);
    }

    public function img_store(StoreImgRequest $request)
    {
        //获取文件--存储文件到reviews/img目录下--再转化为绝对路径
//        $path=Storage::url($request->file->store('reviews/imgs'));

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

    public function store_visitor(Request $request, Product $product)
    {
        $request->validate([
            'rate' => 'required|in:1,2,3,4,5,6,7',
            'buy' => 'required|in:0,1',
            'shop' => 'required|in:0,1,2,3,4'
        ]);

//        $cat_id=$product->cat->id;
        $review = Review::create([
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'brand_id' => $product->brand_id,
            'rate' => $request->rate,
            'body' => '',
            'buy' => $request->buy,
            'shop' => $request->shop,
            'device' => Agent::device(),
            'province'=>Ip::find(request()->ip())[1],
            'city' => Ip::find(request()->ip())[2]
        ]);
        return ['游客' => 'ok', 'updated_at' => $review->updated_at];
    }

    public function store(StoreReviewRequest $request, Product $product)
    {

        if (!$product->has_login_review) $product->update(['has_login_review' => true]);//若此商品之前从未有过登录用户的点评，则把字段改为true

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'brand_id' => $product->brand_id,
            'rate' => $request->rate,
            'body' => request('body', ''),
            'imgs' => json_encode(request('imgs')),
            'buy' => $request->buy,
            'shop' => $request->shop,
            'device' => Agent::device(),
            'province'=>Ip::find(request()->ip())[1],
            'city' => Ip::find(request()->ip())[2]
        ]);

        return ['reviewId' => $review->id, 'updated_at' => $review->updated_at];
    }

    public function update(StoreReviewRequest $request, Product $product, Review $review)
    {
        $this->authorize('update', $review);

        $pre_buy = $review->buy;

        $review->update([
            'rate' => $request->rate,
            'body' => request('body', ''),
            'imgs' => json_encode(request('imgs')),
            'buy' => $request->buy,
            'shop' => $request->shop,
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
                Cache::decrement('b-' . Auth::id() . '-u');
                Cache::decrement('b-' . $product->id . '-p');
                Cache::decrement('b-' . $product->brand_id . '-b');
            } else {
                Cache::increment('b-' . Auth::id() . '-u');
                Cache::increment('b-' . $product->id . '-p');
                Cache::increment('b-' . $product->brand_id . '-b');
            }
        }
//        session()->flash('message','更新成功');
        return ['aa' => '更新点评', 'updated_at' => $review->updated_at];
    }

    public function destroy(Review $review)
    {
        $this->authorize('update', $review);

        $review->delete();

        return ['a'=>'ok'];
    }

}
