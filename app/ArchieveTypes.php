<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ArchieveTypes extends Model
{
    protected $collection = 'archieve_types';

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
