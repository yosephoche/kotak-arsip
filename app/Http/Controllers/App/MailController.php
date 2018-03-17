<?php

namespace App\Http\Controllers\App;
use App\Notifications;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Auth, Session, GlobalClass, Mail;

class MailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function sendMail()
	{
		//Send Mail
		// $user = 'gifa@docotel.com';
		// $data = [
		// 	'fullname' => 'gifa',
		// 	'email' => 'gifa@docotel.com',
		// 	'phone' => '08114441734',
		// 	'company' => 'DTC',
		// 	'messages' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. In illo aut quasi iste laborum qui molestiae unde veritatis incidunt libero.'
		// ];

		// Mail::send('mail.test', $data, function ($mail) use ($user)
		// {
		// 	$mail->to($user);
		// 	$mail->subject('Test mail kotakarsip 2');
		// });

		return view('mail.notifications');
	}
}
