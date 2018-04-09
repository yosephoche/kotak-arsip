<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class UserLoginCode extends Model
{
    protected $collection = 'user_login_code';
}
