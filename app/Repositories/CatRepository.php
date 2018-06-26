<?php

namespace App\Repositories;

use App\Cat;

/**
 * Class CatRepository
 *
 * @package \App\Repositories
 */
class CatRepository
{
    public function all_cats()
    {
        return Cat::select('id','name')->orderBy('id','asc')->get();
    }
}
