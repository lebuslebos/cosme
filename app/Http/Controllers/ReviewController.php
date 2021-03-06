<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Http\Requests\StoreReviewRequest;
use App\Product;
use App\Repositories\CatRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RankingRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Facades\Agent;
use Zhuzhichao\IpLocationZh\Ip;

class ReviewController extends Controller
{
    protected $rankingRepository;
    protected $reviewRepository;
    protected $productRepository;
    protected $catRepository;
    protected $userRepository;

    public function __construct(RankingRepository $rankingRepository, ReviewRepository $reviewRepository, ProductRepository $productRepository, CatRepository $catRepository, UserRepository $userRepository)
    {
        $this->middleware('auth')
            ->except(['index', 'api_index', 'api_img_store', 'api_store', 'api_update', 'api_destroy', 'ranking', 'vote', 'store_visitor']);
        $this->rankingRepository = $rankingRepository;
        $this->reviewRepository = $reviewRepository;
        $this->productRepository = $productRepository;
        $this->catRepository = $catRepository;
        $this->userRepository = $userRepository;
    }


    //所有用户,点赞--点踩的逻辑
    public function vote(Request $request)
    {
        $request->validate([
            'review' => 'required|integer|min:1',
            'user' => 'required|integer|min:1',
            'type' => ['required', Rule::in(['l', 'h'])],
        ]);

        $rand_vote=rand(3, 9);
        //点评的获赞数/获踩数+1-->并各做持久化处理
        Cache::increment($request->type . '-' . $request->review, $rand_vote);
        $r_ids = Cache::get($request->type . '-r-ids', []);
        $r_ids[] = $request->review;
        Cache::forever($request->type . '-r-ids', $r_ids);

        //用户的获赞数/获踩数+1-->并各做持久化处理
        Cache::increment($request->type . '-' . $request->user . '-u',$rand_vote);
        $u_ids = Cache::get($request->type . '-u-ids', []);
        $u_ids[] = $request->user;
        Cache::forever($request->type . '-u-ids', $u_ids);
        //return 'ok';
    }

    public function api_vote(Request $request)
    {

    }

    public function index()
    {
        $reviews = $this->reviewRepository->index();

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

        $b_count=intval(Cache::rememberForever('b-count',function (){
            return Brand::count();
        }));
        $p_count=intval(Cache::rememberForever('p-count',function (){
            return Product::count();
        }));
        $r_count=intval(Cache::rememberForever('r-count',function (){
            return Review::count();
        }));

        //争议差评商品
        $simple_negative_products = Cache::tags('negative')->rememberForever('simple-negative-products', function () {
            return $this->productRepository->simple(config('common.negative_products'),'asc');
        });

        $simple_recent_products=Cache::tags('recent')->rememberForever('simple-recent-products', function () {
            return $this->productRepository->simple(config('common.recent_products'),'desc');
        });


        return compact('reviews','simple_negative_products','simple_recent_products','b_count','p_count','r_count');
    }


    public function ranking(int $cat_id, Request $request)
    {
//        return ['aa'=>$request->type];
        $request->validate(['type' => ['required', Rule::in(['desc', 'asc'])]]);

        return $this->rankingRepository->cached_ranking_by_cat($cat_id, $request->type);
    }

    public function store_visitor(Request $request, Product $product)
    {
        $request->validate([
            'rate' => 'required|in:1,2,3,4,5,6,7',
            'buy' => 'required|in:0,1',
            'shop' => 'required|in:0,1,2,3,4'
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'brand_id' => $product->brand_id,
            'rate' => $request->rate,
            'buy' => $request->buy,
            'shop' => $request->shop,
            'body' => '',
            'device' => Agent::device(),
            'province' => Ip::find(request()->ip())[1],
            'city' => Ip::find(request()->ip())[2]
        ]);
        return ['游客' => 'ok', 'updated_at' => $review->updated_at];
    }


    //pc上传点评图片
    public function img_store(Request $request)
    {
        return $this->reviewRepository->img($request);
    }

    //pc点评
    public function store(StoreReviewRequest $request, Product $product)
    {
        $review = $this->reviewRepository->store($request, $product, Auth::id(), Agent::device());

        return ['reviewId' => $review->id, 'updated_at' => $review->updated_at];
    }

    //微信上传点评图片
    public function api_img_store(Request $request)
    {
        $user = $this->userRepository->get_user(request('openid'));
        if ($user) {
            return $this->reviewRepository->img($request);
        }
    }

    //微信点评
    public function api_store(StoreReviewRequest $request, Product $product)
    {

        if ($user = $this->userRepository->get_user(request('openid'))) {

            $this->reviewRepository->store($request, $product, $user->id, request('brand'), request('model'), request('openid'));

            return ['submitted' => 1];
        }
    }


    public function update(StoreReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $updated_at = $this->reviewRepository->update($request, $review, Auth::id());

        return ['updated_at' => $updated_at];
    }

    public function api_update(StoreReviewRequest $request, Review $review)
    {

        if ($user = $this->userRepository->get_user(request('openid'))) {

            $this->reviewRepository->update($request, $review, $user->id);
            return ['submitted' => 1];
        }
    }

    public function destroy(Review $review)
    {
        $this->authorize('update', $review);

        $this->reviewRepository->destroy($review);

        return ['a' => 'ok'];
    }

    public function api_destroy(Review $review)
    {
        if ($user = $this->userRepository->get_user(request('openid'))) {

            $this->reviewRepository->destroy($review);

            return ['deleted' => 1];
        }
    }

}
