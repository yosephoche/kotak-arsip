<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class UserVerification extends Model
{
    protected $collection = 'users_verification';

    protected $fillable = [
        'user_id', 'token', 'telegram_user'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
