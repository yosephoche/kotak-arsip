<?php

namespace App\Classes;
use App\Posts;
use DB, Session, Response, Image, Auth, DateTime;


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

	function generateIsoDate($date)
	{
		return new \MongoDB\BSON\UTCDateTime(new DateTime($date));
	}

	function arrayObjectId($string)
	{
		$share = [];

		foreach ($string as $s) {
			$share[] = new \MongoDB\BSON\ObjectID($s);
		}
		return $share;
	}

	function OCRKey($image, $result, $open, $key)
	{
		// From
		if ($key == 'from') {
			$from = '';
			$searchfrom = array("LEMBAGA", "KERUKUNAN", "PT");
			$myfile = fopen($open, "r") or die("Unable to open file!");
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

		// Refrence_Number
		if ($key == 'reference_number') {
			$reference_number = '';
			$searchnumber = array("Nomor :", "No. Surat", "No Surat");
			$myfile = fopen($open, "r") or die("Unable to open file!");
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
			return explode(':',$reference_number, 2)[1];
		}

		// Subject
		if ($key == 'subject') {
			$subject = '';
			$searchsubject = array("Perihal :", "Hal :");
			$myfile = fopen($open, "r") or die("Unable to open file!");
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
			return explode(':',$subject, 2)[1];
		}

		// Full Text
		if ($key == 'fulltext') {
			return file_get_contents($open);
		}
	}

}