<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Archieve extends Model
{
    protected $collection = 'archieve';

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
