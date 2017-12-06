<?php

namespace App\Policies;

use App\Acme\Traint\AdminAction;
use App\Buyer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function view(User $user, Buyer $buyer)
    {
        return $user->id == $buyer->id;
    }

    public function purchase(User $user, Buyer $buyer)
    {
        return $user->id == $buyer->id;    
    }

}
