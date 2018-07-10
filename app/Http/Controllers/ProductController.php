<?php

namespace App\Http\Controllers;

use App\Product;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected $rankingRepository;

    public function __construct(RankingRepository $rankingRepository)
    {
        $this->rankingRepository = $rankingRepository;
    }

    public function index()
    {
        $products = Product::with(['cat:id,name', 'brand:id,name,common_name', 'prices'])->paginate(100);
        return view('p', compact('products'));
    }

    public function show(int $product_id)
    {
        //缓存处理--后台修改商品后需清除缓存
        $product = Cache::rememberForever('products-' . $product_id, function () use ($product_id) {
            return Product::find($product_id, ['id', 'cat_id', 'brand_id', 'name', 'nick_name', 'rate','reviews_count','buys_count','has_login_review'])
                ->load(['brand:id,name,common_name', 'cat:id,name', 'prices', 'colors']);
        });

        //排行榜--缓存处理
        $cat = $product->cat;
        $red_products = $this->rankingRepository->cached_ranking_by_cat($cat->id, 'desc', 10);

        if ($product->reviews_count > 0) {
            //所有点评(带分页)--缓存处理(tag:商品-1-点评)
            $reviews = Cache::tags('products-' . $product_id . '-reviews')
                ->rememberForever('products-' . $product_id . '-reviews-' . request('page', 1), function () use ($product) {
                    return $product->reviews()
                        ->select('id', 'user_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count','hates_count','updated_at')
                        ->with('user:id,name,avatar,skin,reviews_count')
                        ->orderByRaw('if(body="",0,1) DESC')
                        ->latest('updated_at')
                        ->orderBy('id', 'desc')
                        ->paginate(config('common.pre_page'));
                });

            //购入场所分布--映射--缓存处理
            $shop_datas = Cache::rememberForever('sh-' . $product_id, function () use ($product) {

                $shops = array_count_values($product->reviews()->pluck('shop')->all());
                $shop_datas = [];
                for ($i = 0; $i < 5; $i++) {
                    $shop_datas[$i] = empty($shops[$i]) ? 0 : ceil(100*$shops[$i]/$product->reviews_count) ;
                }
                return $shop_datas;
            });

            //肤质分布--映射--缓存处理
            if ($product->has_login_review) {
                $skin_datas = Cache::rememberForever('sk-' . $product_id, function () use ($product) {

                    $skins = array_count_values($product->users()->pluck('users.skin')->all());
                    $skin_datas = [];

                    /*$skin_texts = ['中性', '干性', '混合性', '油性', '敏感性', '过敏性'];
                    foreach ($skin_texts as $key => $skin_text) {
                        $skin_datas[$key] = $skins[$skin_text] ?? 0;
                    }*/

                    for ($i = 0; $i < 6; $i++) {
                        $skin_datas[$i] = empty($skins[$i]) ? 0 : ceil(100*$skins[$i]/$product->reviews_count);
                    }
                    return $skin_datas;
                });

            }
        }
        return view('products.show', compact('product', 'cat', 'shop_datas', 'skin_datas', 'reviews', 'red_products'));
    }

    public function api_show(int $product_id)
    {
        //缓存处理--后台修改商品后需清除缓存
        $product = Cache::rememberForever('products-' . $product_id, function () use ($product_id) {
            return Product::find($product_id, ['id', 'cat_id', 'brand_id', 'name', 'nick_name', 'rate','reviews_count','buys_count','has_login_review'])
                ->load(['brand:id,name,common_name', 'cat:id,name', 'prices']);
        });

        if ($product->reviews_count > 0) {
            //所有点评(带分页)--缓存处理(tag:商品-1-点评)
            $reviews = Cache::tags('products-' . $product_id . '-reviews')
                ->rememberForever('products-' . $product_id . '-reviews-' . request('page', 1), function () use ($product) {
                    return $product->reviews()
                        ->select('id', 'user_id', 'rate', 'body', 'imgs', 'buy', 'shop', 'likes_count','hates_count','updated_at')
                        ->with('user:id,name,avatar,skin,reviews_count')
                        ->orderByRaw('if(body="",0,1) DESC')
                        ->latest('updated_at')
                        ->orderBy('id', 'desc')
                        ->simplePaginate(config('common.pre_page_mobile'));
                });

            $will_buy=round(100*$product->buys_count/$product->reviews_count);
            $buy_datas=[$will_buy,100-$will_buy];

            //购入场所分布--映射--缓存处理
            $shop_datas = Cache::rememberForever('sh-' . $product_id, function () use ($product) {

                $shops = array_count_values($product->reviews()->pluck('shop')->all());
                $shop_datas = [];
                for ($i = 0; $i < 5; $i++) {
                    $shop_datas[$i] = empty($shops[$i]) ? 0 : ceil(100*$shops[$i]/$product->reviews_count) ;
                }
                return $shop_datas;
            });

            //肤质分布--映射--缓存处理
            if ($product->has_login_review) {
                $skin_datas = Cache::rememberForever('sk-' . $product_id, function () use ($product) {

                    $skins = array_count_values($product->users()->pluck('users.skin')->all());
                    $skin_datas = [];
                    for ($i = 0; $i < 6; $i++) {
                        $skin_datas[$i] = empty($skins[$i]) ? 0 : ceil(100*$skins[$i]/$product->reviews_count);
                    }
                    return $skin_datas;
                });

            }
        }
        return compact('product', 'buy_datas','shop_datas', 'skin_datas', 'reviews');
    }


}
