<?php

namespace App\Http\Controllers\ChatBot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Telegram\Bot\Api;
use Telegram;

class TelegramBotController extends Controller
{
	public function setWebhook()
	{
		$telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
		$response = $telegram->setWebhook(['url' => 'https://45860da5.ngrok.io/telegrambot/webhook']);

		return 'ok';
	}

	public function keyboard($status = 'show', $keyboardType = 'custom')
	{
		$keyboard = [
			['Cari Arsip', 'Arsip Terbaru'],
			// ['Surat Masuk', 'Surat Keluar'],
		];

		if ($keyboardType == 'number') {
			$keyboard = [
				['1', '2', '3'],
				['4', '5', '6'],
				['7', '8', '9'],
				['Sebelumnya', 'Selanjutnya'],
			];
		}

		$reply_markup = Telegram::replyKeyboardMarkup([
			'keyboard' => $keyboard, 
			'resize_keyboard' => true,
			'one_time_keyboard' => true
		]);

		if ($status == 'hide') {
			$reply_markup = Telegram::replyKeyboardHide();
		}

		if ($status == 'reply') {
			$reply_markup = Telegram::forceReply();
		}

		return $reply_markup;
	}

	public function welcomeText($chatid, $name)
	{
		// Action
		Telegram::sendChatAction([
			'chat_id' => $chatid,
			'action' => 'typing'
		]);

		// Response
		Telegram::sendMessage([
			'chat_id' => $chatid, 
			'text' => 'Hai '.$name.', selamat datang di KotakArsip. Masukkan kata kunci ',
			'reply_markup' => $this->keyboard()
		]);
	}

	public function newArchive($chatid, $text)
	{
		// Data Archives
		$json = file_get_contents('https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=fc84a30b3cb14d55be9b2fe2ad8df133');
		$obj = json_decode($json);
		$listArchives = '';
		foreach ($obj->articles as $key => $archive) {
			$listArchives .= ($key + 1)."️⃣  ".$archive->title."\n\n";
			if ($key >= 8) {
				break;
			}
		}

		// Action
		Telegram::sendChatAction([
			'chat_id' => $chatid,
			'action' => 'typing'
		]);

		Telegram::sendMessage([
			'chat_id' => $chatid, 
			'text' => "Berikut beberapa arsip terbaru Anda\n\n".$listArchives."\n\nMasukkan nomor urut arsip untuk melihat detail",
			'parse_mode' => 'html',
			'reply_markup' => $this->keyboard('hide')
		]);
	}

	public function searchText($chatid)
	{
		// Action
		Telegram::sendChatAction([
			'chat_id' => $chatid,
			'action' => 'typing'
		]);

		// Response
		Telegram::sendMessage([
			'chat_id' => $chatid,
			'text' => 'Masukkan kata kunci atau judul arsip yang ingin Anda cari',
			'reply_markup' => $this->keyboard('reply')
		]);
	}

	public function searchResultText($chatid, $text)
	{
		// Data Archives
		$json = file_get_contents('https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=fc84a30b3cb14d55be9b2fe2ad8df133');
		$obj = json_decode($json);
		$listArchives = '';
		foreach ($obj->articles as $key => $archive) {
			$listArchives .= ($key + 1)."️⃣  ".$archive->title."\n\n";
			if ($key >= 8) {
				break;
			}
		}

		// Action
		Telegram::sendChatAction([
			'chat_id' => $chatid,
			'action' => 'typing'
		]);

		Telegram::sendMessage([
			'chat_id' => $chatid, 
			'text' => "Berikut hasil pencarian untuk kata kunci <b>".$text."</b>\n\n".$listArchives."\n\nMasukkan nomor urut arsip untuk melihat detail",
			'parse_mode' => 'html',
			'reply_markup' => $this->keyboard('hide')
		]);
	}

	public function webhook(Request $r)
	{
		// Get update message
		$telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

		$fromid = $r['message']['from']['id'];
		$chatid = $r['message']['chat']['id'];
		$text = $r['message']['text']; // get the user sent text
		$name = $r['message']['from']['first_name']; // get the user name

		switch($text) {
			case '/start':
				$this->welcomeText($chatid, $name);
				break;
			case '/search':
				$this->searchText($chatid);
				break;
			case 'Cari Arsip':
				$this->searchText($chatid);
				break;
			case 'Arsip Terbaru':
				$this->newArchive($chatid, $text);
				break;
			default:
				$this->searchResultText($chatid, $text);
				break;
		}
	}
}
