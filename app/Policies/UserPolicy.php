<?php 

namespace App\Policies;

use App\Acme\Traint\AdminAction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id == $user->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $authenticatedUser, User $model)
    {
        return $authenticatedUser->id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $authenticatedUser, User $model)
    {
        return $authenticatedUser->id == $user->id && $authenticatedUser->token()->personal_token_client;
    }
}
