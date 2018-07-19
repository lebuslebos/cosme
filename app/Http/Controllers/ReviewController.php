<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Product;
use App\Repositories\CatRepository;
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
    protected $catRepository;
    protected $userRepository;

    public function __construct(RankingRepository $rankingRepository, ReviewRepository $reviewRepository, CatRepository $catRepository, UserRepository $userRepository)
    {
        $this->middleware('auth')
            ->except(['index', 'api_index', 'api_img_store', 'api_store', 'api_update', 'api_destroy', 'ranking', 'vote', 'store_visitor']);
        $this->rankingRepository = $rankingRepository;
        $this->reviewRepository = $reviewRepository;
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
        return compact('reviews');
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
            'body' => '',
            'buy' => $request->buy,
            'shop' => $request->shop,
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
        $review = $this->reviewRepository->store($request, $product, Auth::id());

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

            $this->reviewRepository->store($request, $product, $user->id);

            return ['submitted' => 1];
        }
    }


    public function update(StoreReviewRequest $request, Product $product, Review $review)
    {
        $this->authorize('update', $review);

        $review = $this->reviewRepository->update($request, $product, $review, Auth::id());

        return ['updated_at' => $review->updated_at];
    }

    public function api_update(StoreReviewRequest $request, Product $product, Review $review)
    {

        if ($user = $this->userRepository->get_user(request('openid'))) {

            $this->reviewRepository->update($request, $product, $review, $user->id);
            return ['submitted' => 1];
        }
    }

    public function destroy(Review $review)
    {
        $this->authorize('update', $review);

        $review->delete();

        return ['a' => 'ok'];
    }

    public function api_destroy(Review $review)
    {
        if ($user = $this->userRepository->get_user(request('openid'))) {

            $review->delete();

            return ['deleted'=>1];
        }
    }

}
