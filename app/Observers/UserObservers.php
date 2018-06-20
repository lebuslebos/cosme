<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Cache;

/**
 * Class UserObservers
 *
 * @package \App\Observers
 */
class UserObservers
{
    public function updated(User $user)
    {
        if (Cache::has('users-' . $user->id)) Cache::forget('users-' . $user->id);
//        Cache::tags('products-' . $product_id . '-reviews')
    }

}
