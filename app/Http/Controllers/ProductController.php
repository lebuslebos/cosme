<?php

namespace App\Http\Controllers;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected $rankingRepository;
    protected $productRepository;

    public function __construct(RankingRepository $rankingRepository, ProductRepository $productRepository)
    {
        $this->rankingRepository = $rankingRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = Product::with(['cat:id,name', 'brand:id,name,common_name', 'prices'])->paginate(100);
        return view('p', compact('products'));
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

            $will_buy = round(100 * $product->buys_count / $product->reviews_count);
            $buy_datas = [$will_buy, 100 - $will_buy];

            //购入场所分布--映射--缓存处理
            $shop_datas = $this->productRepository->shop_datas($product_id, $product);

            //肤质分布--映射--缓存处理
            if ($product->has_login_review) $skin_datas = $this->productRepository->skin_datas($product_id, $product);
        }
        return compact('product', 'buy_datas', 'shop_datas', 'skin_datas', 'reviews');
    }


}
