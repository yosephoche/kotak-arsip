<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use GlobalClass;

class Tracker extends Model
{
	public $attributes = ['hits' => 0];

	protected $fillable = ['email', 'company', 'url', 'device'];

	protected $collection = 'visitors';

	public static function boot() {	
		// When a new instance of this model is created...
		static::creating(function ($tracker) {
			$tracker->hits = 0;
		} );

		// Any time the instance is saved (create OR update)
		static::saving(function ($tracker) {
			$tracker->hits++;
		} );
	}

	public static function hit($email, $company) {
		if (stristr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], "/api/") == false && stristr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], "pencarian/autocomplete") == false) {
			// Device Info
			$type_device = get_browser(null, true);
				if ($type_device['ismobiledevice']){
					$device = "Mobile";
				} else {
					$device = "Dekstop";
				}

			static::firstOrCreate([
				'email'		   => $email,
				'company'      => GlobalClass::generateMongoObjectId($company),
				'url'          => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
				'device'       => $device,
			])->save();
		}
	}
}
