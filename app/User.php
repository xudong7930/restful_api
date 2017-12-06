<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;
    public $transformer = UserTransformer::class;
    protected $table = 'users';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name', 'email', 'verified','admin', 'password', 'verify_token'
    ];

    protected $hidden = [
        'password', 'remember_token', 'verify_token'
    ];

    public function isVerified()
    {
        return $this->verified;
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function getEmailAttribute($email)
    {
        return ucwords($email);
    }
}
