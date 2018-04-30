<?php

namespace App\Console\Commands;

use App\Emails, App\User, App\Archieve, App\Share;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail, GlobalClass;

class SendEmails extends Command
{
	protected $signature = 'sending';

	protected $description = 'Sending email notifications';

	public function __construct()
	{
		parent::__construct();
	}

	public function sendMail()
	{
		$emails = Emails::where('status', 0)->get();
		foreach ($emails as $key => $email) {
			// Query Data
			$user = User::find(GlobalClass::generateMongoObjectId($email->id_user));
			$user_from = User::find(GlobalClass::generateMongoObjectId($email->id_user_from));
			$archieve = Archieve::find(GlobalClass::generateMongoObjectId($email->id_archieve));
			$disposition = Share::where('id_archieve', GlobalClass::generateMongoObjectId($email->id_archieve))->where('share_to', GlobalClass::generateMongoObjectId($email->id_user))->select('message')->first();
			// Mail Data
			$sendto = $user->email;
			if (isset($archieve)) {
				$data = [
					'id' => (string)$email->link,
					'fullname' => $user->name,
					'user_from' => $user_from->name,
					'archieve' => $archieve->search,
					'type' => $archieve->type,
					'date' => Carbon::createFromTimestampMs((int)(string)$archieve->date)->format('d/m/Y'),
					'disposition_message' => $disposition->message !== null ? $disposition->message : '-',
					'files' => count($archieve->files),
				];
				if ($archieve->type == 'incoming_mail' || $archieve->type == 'outgoing_mail') {
					$data['subject'] = $archieve->subject != null ? $archieve->subject : '-';
				} else if ($archieve->type == 'file') {
					$data['subject'] = $archieve->desc != null ? $archieve->desc : '-';
				}

				// Indonesian Lang Type
				if ($archieve->type == 'incoming_mail') {
					$data['type_id'] = 'Surat Masuk';
				} else if ($archieve->type == 'outgoing_mail') {
					$data['type_id'] = 'Surat Keluar';
				} else if ($archieve->type == 'file') {
					$data['type_id'] = 'Berkas';
				}

				// Send Mail
				Mail::send('mail.notifications', $data, function ($mail) use ($sendto, $data)
				{
					$mail->to($sendto);
					$mail->subject($data['type_id'].' - '.$data['archieve']);
				});
			}

			// Update data queue
			$update = Emails::find(GlobalClass::generateMongoObjectId($email->_id));
			$update->status = 1;
			$update->save();
		}

	}

	public function handle()
	{
		$this->sendMail();
	}
}
