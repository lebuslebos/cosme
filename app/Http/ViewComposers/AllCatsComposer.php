<?php

namespace App\Http\ViewComposers;

use App\Repositories\CatRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
/**
 * Class AllCatsComposer
 *
 * @package \App\Http\ViewComposers
 */
class AllCatsComposer
{

    protected $catRepository;

    public function __construct(CatRepository $catRepository)
    {
        $this->catRepository = $catRepository;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $all_cats=Cache::rememberForever('all-cats',function (){
            return $this->catRepository->all_cats();
        });
        $view->with(compact('all_cats'));
    }

}
