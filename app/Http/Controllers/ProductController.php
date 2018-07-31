<?php

namespace App\Http\Controllers;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\RankingRepository;
use App\Repositories\UserRepository;

class ProductController extends Controller
{
    protected $rankingRepository;
    protected $productRepository;
    protected $userRepository;

    public function __construct(RankingRepository $rankingRepository, ProductRepository $productRepository, UserRepository $userRepository)
    {
        $this->rankingRepository = $rankingRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        /*$products = Product::with(['cat:id,name', 'brand:id,name,common_name', 'prices'])->paginate(100);
        return view('p', compact('products'));*/
    }

    public function show(int $product_id)
    {
        //缓存处理--后台修改商品后需清除缓存
        $product = $this->productRepository->product($product_id);

        //排行榜--缓存处理
        $cat = $product->cat;
        $red_products = $this->rankingRepository->cached_ranking_by_cat($cat->id, 'desc', 10);

        if ($product->reviews_count > 0) {
            //所有点评(带分页)--缓存处理(tag:商品-1-点评)
            $reviews = $this->productRepository->reviews($product_id, $product);
            $reviews->withPath(request()->url());

            //购入场所分布--映射--缓存处理
            $shop_datas = $this->productRepository->shop_datas($product_id, $product);

            //肤质分布--映射--缓存处理
            if ($product->has_login_review) $skin_datas = $this->productRepository->skin_datas($product_id, $product);

        }
        return view('products.show', compact('product', 'cat', 'shop_datas', 'skin_datas', 'reviews', 'red_products'));
    }

    public function api_show(int $product_id)
    {
        $product = $this->productRepository->product($product_id);

        if ($product->reviews_count > 0) {
            //所有点评(带分页)--缓存处理(tag:商品-1-点评)
            $reviews = $this->productRepository->reviews($product_id, $product);



//            $reviews->withPath('products/'.$product_id);
            $reviews->withPath(request()->url());




            $will_buy = round(100 * $product->buys_count / $product->reviews_count);
            $buy_datas = [$will_buy, 100 - $will_buy];
            /*$most_buy_count = max($buy_datas);//先取最大数，再取出index
            $most_buy = array_random(array_keys($buy_datas, $most_buy_count));*/

            //购入场所分布--映射--缓存处理
            $shop_datas = $this->productRepository->shop_datas($product_id, $product);
            $most_shop_count = max($shop_datas);//先取最大数，再取出index
            $most_shop = array_random(array_keys($shop_datas, $most_shop_count));

            //肤质分布--映射--缓存处理
            if ($product->has_login_review){
                $skin_datas = $this->productRepository->skin_datas($product_id, $product);
                $most_skin_count = max($skin_datas);//先取最大数，再取出index
                $most_skin = array_random(array_keys($skin_datas, $most_skin_count));
            }
        }

        if ($openid = request('openid')) {
            $my_review=$this->review($product_id,$openid);
        }

        return compact('product', 'buy_datas','shop_datas','most_shop_count','most_shop', 'skin_datas', 'most_skin_count','most_skin','my_review','reviews');
    }

    public function api_my_review(int $product_id)
    {
        $my_review=$this->review($product_id,request('openid'));
        return compact('my_review');
    }

    public function review(int $product_id,string $openid)
    {
        $user = $this->userRepository->get_user($openid);
        $my_review = optional($user)->my_review($product_id);
        return $my_review;
    }


}
