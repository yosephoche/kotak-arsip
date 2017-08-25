<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CompanyService extends Model
{
    protected $collection = 'company_service';

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
