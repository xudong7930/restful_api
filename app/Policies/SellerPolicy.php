<?php

namespace App\Policies;

use App\Acme\Traint\AdminAction;
use App\Seller;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the seller.
     *
     * @param  \App\User  $user
     * @param  \App\Seller  $seller
     * @return mixed
     */
    public function view(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    public function sale(User $seller)
    {
        return $user->id === $seller->id;    
    }

    public function editProduct(User $user, Seller $seller)
    {
        
    }

    public function deleteProduct(User $user, Seller $seller)
    {
        
    }
}
