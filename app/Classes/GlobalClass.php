<?php

namespace App\Classes;
use App\Posts;
use DB, Session, Response, Image, Auth;


class GlobalClass
{

	public function Upload ($files, $destinationPath)
	{
		// Variable For Passing
		$filename = [];
		$file_count = count($files);

		for ($i=0; $i < $file_count ; $i++) { 
			// Image Upload Process
			$ext = $files[$i]->getClientOriginalExtension();
			$nm_file = rand(111111,999999).".".$ext;
			$upload = $files[$i]->move($destinationPath, $nm_file);
			$filename[] = $nm_file;
		}

		return $filename;
	}

	function generateMongoObjectId($string)
	{
	    return new \MongoDB\BSON\ObjectID($string);
	}

	function arrayObjectId($string)
	{
		$share = [];

		foreach ($string as $s) {
			$share[] = new \MongoDB\BSON\ObjectID($s);
		}
		return $share;
	}

}