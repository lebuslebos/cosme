<?php

namespace App\Http\ViewComposers;

use App\Cat;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
/**
 * Class AllCatsComposer
 *
 * @package \App\Http\ViewComposers
 */
class AllCatsComposer
{

    protected $cats;

    public function __construct(Cat $cats)
    {
        $this->cats = $cats;
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
            return Cat::select('id','name')->orderBy('id','asc')->get();
        });
        $view->with(compact('all_cats'));
    }

}
