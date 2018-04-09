<?php

namespace App\Classes;
use App\Notifications, App\Users;
use DB, Session, Response, Image, Auth, DateTime, Request;


class GlobalClass
{
	public function formatBytes($size) {
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		$size = array(
				number_format(floor($size / pow(1024, $power)), 0, '.', ','),
				$units[$power],
				($size / 1024) / 1024
			);
		return $size;
	}

	public function dirSize($dir) {
		$size = 0;
		foreach (glob(rtrim($dir, '/').'/*.*', GLOB_NOSORT) as $each) {
			$size += is_file($each) ? filesize($each) : folderSize($each);
		}
		return $size;
	}

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

	public function UploadFile ($files, $destinationPath)
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
			$searchfrom = array("LEMBAGA", "PT", "CV", "PT.", "CV.", "PEMERINTAH", "ORGANISASI", "Kepala Dinas", "Dinas");
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
			$searchto = array("LEMBAGA", "PT", "CV", "PT.", "CV.", "PEMERINTAH", "ORGANISASI", "Kepala Dinas", "Dinas");
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
			$searchsubject = array("Perihal :", "Periha] :", "Hal :", "Perihal:", "Periha]:", "Hal:");
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

	public function getOS($user_agent)
	{
		$os_platform = "Unknown OS Platform";

		$os_array = array(
						'/windows nt 10/i'      =>  'Windows 10',
						'/windows nt 6.3/i'     =>  'Windows 8.1',
						'/windows nt 6.2/i'     =>  'Windows 8',
						'/windows nt 6.1/i'     =>  'Windows 7',
						'/windows nt 6.0/i'     =>  'Windows Vista',
						'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
						'/windows nt 5.1/i'     =>  'Windows XP',
						'/windows xp/i'         =>  'Windows XP',
						'/windows nt 5.0/i'     =>  'Windows 2000',
						'/windows me/i'         =>  'Windows ME',
						'/win98/i'              =>  'Windows 98',
						'/win95/i'              =>  'Windows 95',
						'/win16/i'              =>  'Windows 3.11',
						'/macintosh|mac os x/i' =>  'Mac OS X',
						'/mac_powerpc/i'        =>  'Mac OS 9',
						'/linux/i'              =>  'Linux',
						'/ubuntu/i'             =>  'Ubuntu',
						'/iphone/i'             =>  'iOS',
						'/ipod/i'               =>  'iOS',
						'/ipad/i'               =>  'iOS',
						'/android/i'            =>  'Android',
						'/blackberry/i'         =>  'BlackBerry',
						'/webos/i'              =>  'Mobile'
					);

		foreach ($os_array as $regex => $value)
				if (preg_match($regex, $user_agent))
						$os_platform = $value;

		return $os_platform;
	}

	// function curl_get_contents($url)
	// {
	// 	$ch = curl_init();
	// 	$timeout = 5;

	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	curl_setopt($ch, CURLOPT_HEADER, false);
	// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	// 	$data = curl_exec($ch);

	// 	curl_close($ch);

	// 	return $data;
	// }

}