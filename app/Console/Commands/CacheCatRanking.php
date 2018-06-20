<?php

namespace App\Console\Commands;

use App\Repositories\RankingRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheCatRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranking:cache {cat}'; //cat为cat_id

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache cat ranking';

    protected $rankingRepository;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RankingRepository $rankingRepository)
    {
        parent::__construct();
        $this->rankingRepository=$rankingRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $cat_id=$this->argument('cat');
        //把分类的排行放入缓存
        Cache::tags('ranking')->forever('cats-' . $cat_id . '-desc-5',$this->rankingRepository->ranking_by_cat($cat_id,'desc'));//红榜
        Cache::tags('ranking')->forever('cats-' . $cat_id . '-desc-10',$this->rankingRepository->ranking_by_cat($cat_id,'desc',10));//商品页红榜
        Cache::tags('ranking')->forever('cats-' . $cat_id . '-asc-5',$this->rankingRepository->ranking_by_cat($cat_id,'asc'));//黑榜

    }
}
