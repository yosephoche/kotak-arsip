<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Company extends Model
{
    protected $collection = 'company';

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
