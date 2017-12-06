<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'verified' => (bool)$user->verified,
            'isAdmin' => (bool)$user->admin,
            'createAt' => (string)$user->created_at,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id)
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'verified' => 'verified',
            'isAdmin' => 'admin',
            'createAt' => 'created_at',
        ];
        return $attributes[$index] ?: null;
    }

    public static function transformAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'verified' => 'verified',
            'admin' => 'isAdmin',
            'created_at' => 'createAt',
        ];
        return $attributes[$index] ?: null;
    }
}
