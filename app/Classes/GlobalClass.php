<?php

namespace App\Classes;
use App\Notifications, App\Users;
use DB, Session, Response, Image, Auth, DateTime, Request;


class GlobalClass
{

	public function Upload ($files, $destinationPath, $thumb)
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

			// Create Thumbnail
			Image::make($destinationPath.'/'.$nm_file,array(
				'width' => $thumb,
				'grayscale' => false
			))->save($destinationPath.'/thumb-'.$nm_file);
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

	function generateIsoDate($date)
	{
		return new \MongoDB\BSON\UTCDateTime(new DateTime($date));
	}

	function arrayIsoDate($string)
	{
		$date = [];

		foreach ($string as $s) {
			$date[] = new \MongoDB\BSON\UTCDateTime(new DateTime($s));
		}
		return $date;
	}

	function OCRKey($image, $result, $open, $key)
	{
		// From
		if ($key == 'from') {
			$from = '';
			$searchfrom = array("LEMBAGA", "KERUKUNAN", "PT", "CV", "PT.", "CV.", "PEMERINTAH", "ORGANISASI");
			@$myfile = fopen($open, "r") or die("Unable to open file!");
			while(!feof($myfile)) 
			{
				$buffer =  fgets($myfile);
				for ($i=0; $i < count($searchfrom) ; $i++) { 
					if(strpos($buffer, $searchfrom[$i]) !== FALSE) {
						if ($from != '') {
							break;
						}
						$from = $buffer;
					}
				}
			}
			fclose($myfile);
			return $from;
		}

		// To
		if ($key == 'to') {
			$to = '';
			$searchto = array("LEMBAGA", "KERUKUNAN", "PT", "CV", "PT.", "CV.", "PEMERINTAH", "ORGANISASI");
			@$myfile = fopen($open, "r") or die("Unable to open file!");
			while(!feof($myfile)) 
			{
				$buffer =  fgets($myfile);
				for ($i=0; $i < count($searchto) ; $i++) { 
					if(strpos($buffer, $searchto[$i]) !== FALSE) {
						if ($to != '') {
							break;
						}
						$to = $buffer;
					}
				}
			}
			fclose($myfile);
			return $to;
		}

		// Refrence_Number
		if ($key == 'reference_number') {
			$reference_number = '';
			$searchnumber = array("Nomor :", "Nomor:", "Nomer :", "Nomer:", "No. Surat :", "No Surat :", "No. Surat:", "No Surat:");
			@$myfile = fopen($open, "r") or die("Unable to open file!");
			while(!feof($myfile)) 
			{
				$buffer =  fgets($myfile);
				for ($i=0; $i < count($searchnumber) ; $i++) { 
					if(strpos($buffer, $searchnumber[$i]) !== FALSE) {
						$reference_number = $buffer;
					}
				}
			}
			fclose($myfile);
			if ($reference_number != '') {
				return explode(':',$reference_number, 2)[1];
			} else {
				return $reference_number;
			}
		}

		// Subject
		if ($key == 'subject') {
			$subject = '';
			$searchsubject = array("Perihal :", "Hal :", "Perihal:", "Hal:");
			@$myfile = fopen($open, "r") or die("Unable to open file!");
			while(!feof($myfile)) 
			{
				$buffer =  fgets($myfile);
				for ($i=0; $i < count($searchsubject) ; $i++) { 
					if(strpos($buffer, $searchsubject[$i]) !== FALSE) {
						$subject = $buffer;
					}
				}
			}
			fclose($myfile);
			if ($subject != '') {
				return explode(':',$subject, 2)[1];
			} else {
				return $subject;
			}
		}

		// Full Text
		if ($key == 'fulltext') {
			return file_get_contents($open);
		}
	}

	public function removeGetParam($param)
	{
		$x = Request::fullUrl();

		$parsed = parse_url($x);
		@$query = $parsed['query'];

		parse_str($query, $params);

		unset($params[$param]);
		$string = http_build_query($params);

		$link = Request::url().'?'.$string;

		return $link;
	}

	public function notif($user, $message, $link)
	{
		$notif = new Notifications;
		$notif->from = new \MongoDB\BSON\ObjectID(Auth::user()->_id);
		$notif->id_user = $user;
		$notif->message = $message;
		$notif->link = $link;
		$notif->read = 0;
		$notif->save();
	}

}