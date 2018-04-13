<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Invoice extends Model
{
    protected $collection = 'invoice';
}
