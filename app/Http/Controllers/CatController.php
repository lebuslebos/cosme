<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Repositories\RankingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatController extends Controller
{
    protected $rankingRepository;

    public function __construct(RankingRepository $rankingRepository)
    {
        $this->rankingRepository=$rankingRepository;
    }

    public function api_index()
    {
        $all_cats=Cache::rememberForever('all-cats',function (){
            return Cat::select('id','name')->orderBy('id','asc')->get();
        });
        return compact('all_cats');
    }

    public function show(int $cat_id)
    {

        $cat = Cache::rememberForever('cats-' . $cat_id, function () use ($cat_id) {
            return Cat::find($cat_id,['id','name','similar_name']);
        });
        //所有商品(带分页)--缓存处理(tag:分类-1-商品)
        $products = Cache::tags('cats-' . $cat_id . '-products')
            ->rememberForever('cats-' . $cat_id . '-products-' . request('page', 1), function () use ($cat) {
                return $cat->products()
                    ->select('id','brand_id','name','nick_name','rate','reviews_count','buys_count')
                    ->with(['brand:id,name,common_name','prices'])
                    ->orderBy('reviews_count','desc') //此处的数字是数据库中的旧数据，页面上显示的是最新缓存数据，因此页面上排名可能不是按最新排的
                    ->orderBy('id','asc')
                    ->paginate(config('common.pre_page'));
            });


        $red_products=$this->rankingRepository->cached_ranking_by_cat($cat_id,'desc');

        $black_products=$this->rankingRepository->cached_ranking_by_cat($cat_id,'asc');

        return view('cats.show',compact('cat','products','red_products','black_products'));
    }

    public function api_show(int $cat_id)
    {
        $cat = Cache::rememberForever('cats-' . $cat_id, function () use ($cat_id) {
            return Cat::find($cat_id,['id','name','similar_name']);
        });
        //所有商品(带分页)--缓存处理(tag:分类-1-商品)
        $products = Cache::tags('cats-' . $cat_id . '-products')
            ->rememberForever('cats-' . $cat_id . '-products-' . request('page', 1), function () use ($cat) {
                return $cat->products()
                    ->select('id','brand_id','name','nick_name','rate','reviews_count','buys_count')
                    ->with(['brand:id,name,common_name','prices'])
                    ->orderBy('reviews_count','desc') //此处的数字是数据库中的旧数据，页面上显示的是最新缓存数据，因此页面上排名可能不是按最新排的
                    ->orderBy('id','asc')
                    ->paginate(config('common.pre_page_mobile'));
            });



        return compact('cat','products');
    }
}
