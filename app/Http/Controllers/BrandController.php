<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    protected $rankingRepository;

    public function __construct(RankingRepository $rankingRepository)
    {
        $this->rankingRepository = $rankingRepository;
    }

    public function index()
    {
        $brands=Brand::paginate(50);
        return view('b',compact('brands'));
    }

    public function show(int $brand_id)
    {
        $brand = Cache::rememberForever('brands-' . $brand_id, function () use ($brand_id) {
            return Brand::find($brand_id,['id','name','common_name','country','country_id','official_website']);
        });
        //所有商品(带分页)--缓存处理(tag:品牌-1-商品)
        $products = Cache::tags('brands-' . $brand_id . '-products')
            ->rememberForever('brands-' . $brand_id . '-products-' . request('page', 1), function () use ($brand) {
                return $brand->products()
                    ->select('id','cat_id','name','nick_name','rate')
                    ->with(['cat:id,name', 'prices'])
                    ->orderBy('reviews_count', 'desc')//此处的数字是数据库中的旧数据，页面上显示的是最新缓存数据，因此页面上排名可能不是按最新排的
                    ->orderBy('id','asc')
                    ->paginate(config('common.pre_page'));
            });

//        $brand->products()->with(['cat:id,name'])->orderBy('reviews_count', 'desc');

        $red_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'desc');

        $black_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'asc');

        return view('brands.show', compact('brand', 'products', 'red_products', 'black_products'));
    }

}
