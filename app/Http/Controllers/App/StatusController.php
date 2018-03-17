<?php

namespace App\Http\Controllers\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, GlobalClass, File;

class StatusController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function capacity()
	{
		// Get Size
		$type = ['incoming_mail', 'outgoing_mail', 'file'];
		$files = 0;
		for ($i=0; $i < count($type); $i++) { 
			$data['bytes_'.$type[$i]] = GlobalClass::dirSize('files/'.Auth::user()->id_company.'/'.$type[$i]);
			$data['size_'.$type[$i]] = GlobalClass::formatBytes($data['bytes_'.$type[$i]]);
			$files += GlobalClass::dirSize('files/'.Auth::user()->id_company.'/'.$type[$i]);
		}
		$file_size = GlobalClass::formatBytes($files);

		// Employee
		$employee = 0;
		foreach( File::allFiles('files/'.Auth::user()->id_company.'/employee/') as $file)
		{
			$employee += $file->getSize();
		}
		$data['size_employee'] = GlobalClass::formatBytes($employee);

		// Total Size
		$files = $files + $employee;
		$file_size = GlobalClass::formatBytes($files);
		$data['size'] = $file_size;

		// Get percentage
		@$data['percentage'] = 100 / (21474836480 / $files);
		@$data['percentage_employee'] = 100 / ($files / $employee);
		for ($i=0; $i < count($type); $i++) {
			@$data['percentage_'.$type[$i]] = 100 / ($files / $data['bytes_'.$type[$i]]);
		}

		return view('app.status.capacity', $data);
	}
}
