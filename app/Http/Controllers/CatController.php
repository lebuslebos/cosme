<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Repositories\CatRepository;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatController extends Controller
{
    protected $rankingRepository;
    protected $catRepository;

    public function __construct(RankingRepository $rankingRepository,CatRepository $catRepository)
    {
        $this->rankingRepository=$rankingRepository;
        $this->catRepository=$catRepository;
    }

    public function api_index()
    {
        $all_cats=$this->catRepository->all_cats();
        return compact('all_cats');
    }

    public function show(int $cat_id)
    {
        $cat = $this->catRepository->cat($cat_id);

        //所有商品(带分页)--缓存处理(tag:分类-1-商品)
        $products = $this->catRepository->products($cat_id,$cat);

        $red_products=$this->rankingRepository->cached_ranking_by_cat($cat_id,'desc');

        $black_products=$this->rankingRepository->cached_ranking_by_cat($cat_id,'asc');

        return view('cats.show',compact('cat','products','red_products','black_products'));
    }

    public function api_show(int $cat_id)
    {
        $cat = $this->catRepository->cat($cat_id);

        $products = $this->catRepository->products($cat_id,$cat);

        return compact('cat','products');
    }
}
