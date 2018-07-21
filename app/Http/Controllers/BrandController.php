<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Repositories\BrandRepository;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    protected $rankingRepository;
    protected $brandRepository;

    public function __construct(RankingRepository $rankingRepository, BrandRepository $brandRepository)
    {
        $this->rankingRepository = $rankingRepository;
        $this->brandRepository = $brandRepository;
    }

    public function index()
    {
        $brands = Brand::paginate(50);
        return view('b', compact('brands'));
    }

    public function show(int $brand_id)
    {
        $brand = $this->brandRepository->brand($brand_id);

        //所有商品(带分页)--缓存处理(tag:品牌-1-商品)
        $products = $this->brandRepository->products($brand_id, $brand);

        $red_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'desc');
        $black_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'asc');

        return view('brands.show', compact('brand', 'products', 'red_products', 'black_products'));
    }

    public function api_show(int $brand_id)
    {
        $brand = $brand = $this->brandRepository->brand($brand_id);

        $products = $this->brandRepository->products($brand_id, $brand);

        $red_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'desc');
        $black_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'asc');

        return compact('brand', 'products', 'red_products', 'black_products');
    }

    public function api_index()
    {
        $hot_brands = Cache::rememberForever('hot-brands', function () {
            return Brand::select('id', 'name')->orderBy('reviews_count', 'desc')->orderBy('buys_count', 'desc')->orderBy('id', 'asc')->take(8)->get();
        });


        $all_brands = Cache::rememberForever('all-brands', function () {
            return Brand::select('id', 'name','country_id')->get();
        });
        $country_brands = Cache::rememberForever('country-brands', function () use ($all_brands) {
            $country_brands = [];
            foreach (config('common.big_brands') as $big_brand) {
                $country_brands[] = $all_brands->where('country_id', $big_brand);
            }
            $country_brands[] = $all_brands->whereNotIn('id',config('common.big_brands'));
            return $country_brands;
        });


        return compact('hot_brands', 'country_brands');

    }
}
