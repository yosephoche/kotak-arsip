<?php

use App\Http\Middleware\MemberPermissions;
use App\Http\Middleware\StoragePermissions;
use App\Http\Middleware\CheckSerial;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

//Refresh CSRF-Token
Route::get('refresh-csrf', function(){
    return csrf_token();
});

//Activation
Route::get('/activation', 'ActivationController@index')->name('activation');
Route::post('/activation/store', 'ActivationController@store')->name('activation_store');

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

// Route::group(['namespace' => 'App', 'middleware' => CheckSerial::class], function () {
Route::group(['namespace' => 'App'], function () {
	//API
	Route::group(['prefix' => 'api'], function(){
		//API-Incoming_Mail
		Route::group(['prefix' => 'surat/masuk'], function(){
			Route::get('/', 'IncomingMailController@getData')->name('api_incoming_mail');
			Route::get('/detail/{id?}', 'IncomingMailController@getDetail')->name('api_incoming_mail_detail');
			Route::get('/riwayat-disposisi/{id?}', 'IncomingMailController@getDetailDisposition')->name('api_incoming_mail_detail_disposition');
		});

		//API-Outgoing_Mail
		Route::group(['prefix' => 'surat/keluar'], function(){
			Route::get('/', 'OutgoingMailController@getData')->name('api_outgoing_mail');
			Route::get('/detail/{id?}', 'OutgoingMailController@getDetail')->name('api_outgoing_mail_detail');
			Route::get('/riwayat-bagikan/{id?}', 'OutgoingMailController@getDetailShared')->name('api_outgoing_mail_detail_shared');
		});

		//API-File
		Route::group(['prefix' => 'berkas'], function(){
			Route::get('/', 'FileController@getData')->name('api_file');
			Route::get('/detail/{id?}', 'FileController@getDetail')->name('api_file_detail');
			Route::get('/riwayat-bagikan/{id?}', 'FileController@getDetailShared')->name('api_file_detail_shared');
		});

		//API-Shared Incoming Mail
		Route::group(['prefix' => 'berbagi/surat/masuk'], function(){
			Route::get('/', 'SharedController@getDataIncomingMail')->name('api_shared_incoming_mail');
			Route::get('/detail/{id?}', 'SharedController@getDetailIncomingMail')->name('api_shared_incoming_mail_detail');
		});

		//API-Shared Outgoing Mail
		Route::group(['prefix' => 'berbagi/surat/keluar'], function(){
			Route::get('/', 'SharedController@getDataOutgoingMail')->name('api_shared_outgoing_mail');
			Route::get('/detail/{id?}', 'SharedController@getDetailOutgoingMail')->name('api_shared_outgoing_mail_detail');
		});

		//API-Shared Files
		Route::group(['prefix' => 'berbagi/berkas'], function(){
			Route::get('/', 'SharedController@getDataFile')->name('api_shared_file');
			Route::get('/detail/{id?}', 'SharedController@getDetailFile')->name('api_shared_file_detail');
		});

		//API-Storage
		Route::group(['prefix' => 'penyimpanan-arsip'], function(){
			Route::get('/', 'StorageController@getData')->name('api_storage');
		});

		//API-Storage-Sub
		Route::group(['prefix' => 'penyimpanan-arsip/sub'], function(){
			Route::get('/{id?}', 'StorageSubController@getData')->name('api_storage_sub');
		});

		//API-Member
		Route::group(['prefix' => 'anggota'], function(){
			Route::get('/', 'MemberController@getData')->name('api_member');
		});


		//API-Search
		Route::group(['prefix' => 'pencarian'], function(){
			Route::get('/', 'SearchController@getData')->name('api_search');
			Route::get('/autocomplete', 'SearchController@getDataAutocomplete')->name('api_search_autocomplete');
		});


		//API-Trash
		Route::group(['prefix' => 'sampah'], function(){
			Route::get('/', 'TrashController@getData')->name('api_trash');
		});


		//API-Folder
		Route::group(['prefix' => 'folder'], function(){
			Route::get('/', 'FolderController@getData')->name('api_folder');
			Route::get('/{folder?}', 'FolderController@getDataDetail')->name('api_folder_detail');
		});
	});

	//Company
	Route::group(['prefix' => 'perusahaan'], function(){
		Route::get('/register', 'CompanyController@register')->name('company_register');
		Route::post('/store', 'CompanyController@store')->name('company_store');
		Route::get('/register/success', 'CompanyController@registerSuccess')->name('company_register_success');
	});

	//Archieve-Types
	Route::group(['prefix' => 'achieve/type/'], function(){
		Route::get('/register', 'ArchieveTypesController@register')->name('archieve_type_register');
		Route::post('/store', 'ArchieveTypesController@store')->name('archieve_type_store');
	});

	//Storage
	Route::group(['prefix' => 'penyimpanan-arsip', 'middleware' => StoragePermissions::class], function(){
		Route::get('/', 'StorageController@index')->name('storage');
		Route::get('/register', 'StorageController@register')->name('storage_register');
		Route::get('/success', 'StorageController@success')->name('storage_register_success');
		Route::post('/store', 'StorageController@store')->name('storage_store');
		Route::post('/update', 'StorageController@update')->name('storage_update');
		Route::post('/delete', 'StorageController@delete')->name('storage_delete');
	});

	//Storage-Sub
	Route::group(['prefix' => 'penyimpanan-arsip/sub', 'middleware' => StoragePermissions::class], function(){
		Route::get('/register', 'StorageSubController@register')->name('storage_sub_register');
		Route::post('/register/store', 'StorageSubController@registerstore')->name('storage_sub_register_store');
		Route::get('/{id?}', 'StorageSubController@index')->name('storage_sub');
		Route::post('/store', 'StorageSubController@store')->name('storage_sub_store');
		Route::post('/update', 'StorageSubController@update')->name('storage_sub_update');
		Route::post('/delete', 'StorageSubController@delete')->name('storage_sub_delete');
	});

	//Mail
	Route::group(['prefix' => 'surat'], function(){
		//Incoming Mail
		Route::group(['prefix' => 'masuk'], function(){
			Route::get('/', 'IncomingMailController@index')->name('incoming_mail');
			Route::post('/upload', 'IncomingMailController@upload')->name('incoming_mail_upload');
			Route::post('/upload/ajax', 'IncomingMailController@uploadAjax')->name('incoming_mail_upload_ajax');
			Route::post('/replace/ajax', 'IncomingMailController@replaceAjax')->name('incoming_mail_replace_ajax');
			Route::post('/replace/edit', 'IncomingMailController@replaceEdit')->name('incoming_mail_replace_edit');
			Route::post('/delete/ajax', 'IncomingMailController@removeAjax')->name('incoming_mail_delete_ajax');
			Route::get('/dropdown', 'IncomingMailController@dropdownAjax')->name('incoming_mail_substorage');
			Route::get('/create', 'IncomingMailController@create')->name('incoming_mail_create');
			Route::post('/store', 'IncomingMailController@store')->name('incoming_mail_store');
			Route::get('/detail/{id?}', 'IncomingMailController@detail')->name('incoming_mail_detail');
			Route::get('/move/{id?}', 'IncomingMailController@move')->name('incoming_mail_move');
			Route::get('/edit/{id?}', 'IncomingMailController@edit')->name('incoming_mail_edit');
			Route::post('/update/{id?}', 'IncomingMailController@update')->name('incoming_mail_update');
			Route::post('/delete', 'IncomingMailController@delete')->name('incoming_mail_delete');
			Route::post('/disposisi', 'IncomingMailController@disposition')->name('incoming_mail_disposition');
			Route::get('/disposisi/delete/{id?}/{id_user?}/{id_archieve?}', 'IncomingMailController@dispositionDelete')->name('incoming_mail_disposition_delete');
			Route::get('/riwayat-disposisi/{id?}', 'IncomingMailController@dispositionHistory')->name('incoming_mail_disposition_history');
		});

		//Outgoing Mail
		Route::group(['prefix' => 'keluar'], function(){
			Route::get('/', 'OutgoingMailController@index')->name('outgoing_mail');
			Route::post('/upload', 'OutgoingMailController@upload')->name('outgoing_mail_upload');
			Route::post('/upload/ajax', 'OutgoingMailController@uploadAjax')->name('outgoing_mail_upload_ajax');
			Route::post('/replace/ajax', 'OutgoingMailController@replaceAjax')->name('outgoing_mail_replace_ajax');
			Route::post('/replace/edit', 'OutgoingMailController@replaceEdit')->name('outgoing_mail_replace_edit');
			Route::post('/delete/ajax', 'OutgoingMailController@removeAjax')->name('outgoing_mail_delete_ajax');
			Route::get('/dropdown', 'OutgoingMailController@dropdownAjax')->name('outgoing_mail_substorage');
			Route::get('/create', 'OutgoingMailController@create')->name('outgoing_mail_create');
			Route::post('/store', 'OutgoingMailController@store')->name('outgoing_mail_store');
			Route::get('/detail/{id?}', 'OutgoingMailController@detail')->name('outgoing_mail_detail');
			Route::get('/move/{id?}', 'OutgoingMailController@move')->name('outgoing_mail_move');
			Route::get('/edit/{id?}', 'OutgoingMailController@edit')->name('outgoing_mail_edit');
			Route::post('/update/{id?}', 'OutgoingMailController@update')->name('outgoing_mail_update');
			Route::post('/delete', 'OutgoingMailController@delete')->name('outgoing_mail_delete');
			Route::post('/bagikan', 'OutgoingMailController@shared')->name('outgoing_mail_shared');
			Route::get('/bagikan/delete/{id?}/{id_user?}/{id_archieve?}', 'OutgoingMailController@sharedDelete')->name('outgoing_mail_shared_delete');
			Route::get('/riwayat-bagikan/{id?}', 'OutgoingMailController@sharedHistory')->name('outgoing_mail_shared_history');
		});

	});

	//Outgoing Mail
	Route::group(['prefix' => 'berkas'], function(){
		Route::get('/', 'FileController@index')->name('file');
		Route::post('/store', 'FileController@store')->name('file_store');
		Route::get('/detail/{id?}', 'FileController@detail')->name('file_detail');
		Route::post('/update/{id?}', 'FileController@update')->name('file_update');
		Route::post('/delete', 'FileController@delete')->name('file_delete');
		Route::post('/bagikan', 'FileController@shared')->name('file_shared');
		Route::get('/bagikan/delete/{id?}/{id_user?}/{id_archieve?}', 'FileController@sharedDelete')->name('file_shared_delete');
		Route::get('/riwayat-bagikan/{id?}', 'FileController@sharedHistory')->name('file_shared_history');
	});

	//Folder
	Route::group(['prefix' => 'folder'], function(){
		Route::get('/', 'FolderController@index')->name('folder');
		Route::get('/{folder?}', 'FolderController@detail')->name('folder_detail');
		Route::post('/update', 'FolderController@update')->name('folder_update');
		Route::post('/delete', 'FolderController@delete')->name('folder_delete');
	});

	//Share
	Route::group(['prefix' => 'berbagi'], function(){
		//Incoming Mail
		Route::group(['prefix' => 'surat/masuk'], function(){
			Route::get('/', 'SharedController@index')->name('shared_incoming_mail');
			Route::get('/detail/{id?}', 'SharedController@detail')->name('shared_incoming_mail_detail');
			Route::post('/delete', 'SharedController@delete')->name('shared_incoming_mail_delete');
			Route::post('/delete-detail', 'SharedController@deleteDetail')->name('shared_incoming_mail_delete_detail');
			Route::get('/disposition/history/{id?}', 'IncomingMailController@dispositionHistory')->name('shared_incoming_mail_disposition_history');
		});

		//Outgoing Mail
		Route::group(['prefix' => 'surat/keluar'], function(){
			Route::get('/', 'SharedController@index')->name('shared_outgoing_mail');
			Route::get('/detail/{id?}', 'SharedController@detail')->name('shared_outgoing_mail_detail');
			Route::post('/delete', 'SharedController@delete')->name('shared_outgoing_mail_delete');
			Route::post('/delete-detail', 'SharedController@deleteDetail')->name('shared_outgoing_mail_delete_detail');
			Route::get('/shared/history/{id?}', 'IncomingMailController@dispositionHistory')->name('shared_outgoing_mail_shared_history');
		});

		//Outgoing Mail
		Route::group(['prefix' => 'berkas'], function(){
			Route::get('/', 'SharedController@index')->name('shared_file');
			Route::get('/detail/{id?}', 'SharedController@detail')->name('shared_file_detail');
			Route::post('/delete', 'SharedController@delete')->name('shared_file_delete');
			Route::post('/delete-detail', 'SharedController@deleteDetail')->name('shared_file_delete_detail');
			Route::get('/shared/history/{id?}', 'FileController@dispositionHistory')->name('shared_file_shared_history');
		});
	});

	//Member
	Route::group(['prefix' => 'anggota', 'middleware' => MemberPermissions::class], function(){
		Route::get('/', 'MemberController@index')->name('member');
		Route::get('/create', 'MemberController@create')->name('member_create');
		Route::post('/store', 'MemberController@store')->name('member_store');
		Route::get('/edit/{id?}', 'MemberController@edit')->name('member_edit');
		Route::put('/update/{id?}', 'MemberController@update')->name('member_update');
		Route::post('/delete', 'MemberController@delete')->name('member_delete');
	});

	//Setting
	Route::group(['prefix' => 'pengaturan'], function(){
		Route::get('/', 'SettingController@index')->name('setting');
		Route::post('/update/user', 'SettingController@updateuser')->name('update_user');
		Route::post('/update/password', 'SettingController@updatepassword')->name('update_password');
		Route::post('/update/company', 'SettingController@updatecompany')->name('update_company');
	});

	//Trash
	Route::group(['prefix' => 'sampah'], function(){
		Route::get('/', 'TrashController@index')->name('trash');
		Route::get('/restore/{id?}', 'TrashController@restore')->name('trash_restore');
		Route::post('/delete', 'TrashController@delete')->name('trash_delete');
		Route::post('/deletekeepfiles', 'TrashController@deleteKeepFiles')->name('trash_delete_keep_files');
	});

	//Search
	Route::group(['prefix' => 'pencarian'], function(){
		Route::get('/', 'SearchController@index')->name('search');
		Route::get('/dropdown', 'SearchController@dropdownAjax')->name('search_substorage');
		Route::post('/delete', 'SearchController@delete')->name('search_delete');
	});

	//Notifications
	Route::group(['prefix' => 'notifications'], function(){
		Route::get('/', 'NotificationsController@index')->name('notifications');
		Route::get('/read-all', 'NotificationsController@readAll')->name('notifications_readall');
	});

	//Help
	Route::group(['prefix' => 'bantuan'], function(){
		Route::get('/', 'HelpController@index')->name('help');
		Route::get('/teknologi-ocr', 'HelpController@ocr')->name('help_ocr');
		Route::get('/koneksi-server', 'HelpController@server')->name('help_server');
		Route::get('/penyimpanan-arsip', 'HelpController@storage')->name('help_storage');
		Route::get('/surat-masuk', 'HelpController@incoming_mail')->name('help_incoming_mail');
		Route::get('/surat-masuk/tambah-sunting', 'HelpController@incoming_mail_create')->name('help_incoming_mail_create');
		Route::get('/surat-masuk/hapus', 'HelpController@incoming_mail_delete')->name('help_incoming_mail_delete');
		Route::get('/surat-masuk/disposisi', 'HelpController@incoming_mail_disposition')->name('help_incoming_mail_disposition');
		Route::get('/surat-keluar', 'HelpController@outgoing_mail')->name('help_outgoing_mail');
		Route::get('/surat-keluar/tambah-sunting', 'HelpController@outgoing_mail_create')->name('help_outgoing_mail_create');
		Route::get('/surat-keluar/hapus', 'HelpController@outgoing_mail_delete')->name('help_outgoing_mail_delete');
		Route::get('/jenis-arsip', 'HelpController@archives')->name('help_archives');
		Route::get('/pencarian', 'HelpController@search')->name('help_search');
		Route::get('/bagikan', 'HelpController@share')->name('help_share');
		Route::get('/folder', 'HelpController@folder')->name('help_folder');
		Route::get('/berkas', 'HelpController@file')->name('help_file');
	});
});
