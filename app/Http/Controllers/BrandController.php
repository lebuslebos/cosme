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
        $products = $this->brandRepository->products($brand_id,$brand);

        $red_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'desc');
        $black_products = $this->rankingRepository->cached_ranking_by_brand($brand_id, 'asc');

        return view('brands.show', compact('brand', 'products', 'red_products', 'black_products'));
    }

    public function api_show(int $brand_id)
    {
        $brand = $brand = $this->brandRepository->brand($brand_id);

        $products = $this->brandRepository->products($brand_id,$brand);

        return compact('brand', 'products');
    }

    public function api_hot()
    {
        $hot_brands = Cache::rememberForever('hot-brands', function () {
            return Brand::select('id', 'name')->orderBy('reviews_count', 'desc')->orderBy('buys_count', 'desc')->orderBy('id', 'asc')->take(8)->get();
        });
        return compact('hot_brands');
    }

    public function api_index()
    {
        $country_ids = [2,3,5,10,1];

        $us_brands = $this->brandRepository->country_brands('us', $country_ids[0]);
        $jp_brands = $this->brandRepository->country_brands('jp', $country_ids[1]);
        $fr_brands = $this->brandRepository->country_brands('fr', $country_ids[2]);
        $kr_brands = $this->brandRepository->country_brands('kr', $country_ids[3]);
        $cn_brands = $this->brandRepository->country_brands('cn', $country_ids[4]);

        $other_brands = Cache::rememberForever('other-brands', function () use ($country_ids) {
            return Brand::select('id', 'name')->whereNotIn('country_id', $country_ids)
                ->orderBy('reviews_count', 'desc')->orderBy('buys_count', 'desc')->orderBy('id', 'asc')
                ->get();
        });

        return compact('us_brands','jp_brands','fr_brands','kr_brands','cn_brands','other_brands');

    }
}
