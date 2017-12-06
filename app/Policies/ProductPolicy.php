<?php

namespace App\Policies;

use App\Acme\Traint\AdminAction;
use App\Product;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function addCategory(User $user, Product $product)
    {
        return $user->id == $product->seller_id;
    }

    public function deleteCategory(User $user, Product $product)
    {
        return $user->id == $product->seller_id;    
    }

}
