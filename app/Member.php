<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Member extends Model
{
    protected $collection = 'users';

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
