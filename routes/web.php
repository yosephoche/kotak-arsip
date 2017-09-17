<?php

use App\Http\Middleware\MemberPermissions;
use App\Http\Middleware\StoragePermissions;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'App'], function () {
	//API
	Route::group(['prefix' => 'api'], function(){
		//API-Incoming_Mail
		Route::group(['prefix' => 'surat/masuk'], function(){
			Route::get('/', 'IncomingMailController@getData')->name('api_incoming_mail');
			Route::get('/detail/{id?}', 'IncomingMailController@getDetail')->name('api_incoming_mail_detail');
		});

		//API-Storage
		Route::group(['prefix' => 'storage'], function(){
			Route::get('/', 'StorageController@getData')->name('api_storage');
		});

		//API-Storage-Sub
		Route::group(['prefix' => 'storage/sub'], function(){
			Route::get('/{id?}', 'StorageSubController@getData')->name('api_storage_sub');
		});

		//API-Member
		Route::group(['prefix' => 'member'], function(){
			Route::get('/', 'MemberController@getData')->name('api_member');
		});
	});

	//Company
	Route::group(['prefix' => 'company'], function(){
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
	Route::group(['prefix' => 'storage', 'middleware' => StoragePermissions::class], function(){
		Route::get('/', 'StorageController@index')->name('storage');
		Route::get('/register', 'StorageController@register')->name('storage_register');
		Route::get('/success', 'StorageController@success')->name('storage_register_success');
		Route::post('/store', 'StorageController@store')->name('storage_store');
		Route::post('/update', 'StorageController@update')->name('storage_update');
		Route::post('/delete', 'StorageController@delete')->name('storage_delete');
	});

	//Storage-Sub
	Route::group(['prefix' => 'storage/sub', 'middleware' => StoragePermissions::class], function(){
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
			Route::post('/delete/ajax', 'IncomingMailController@removeAjax')->name('incoming_mail_delete_ajax');
			Route::get('/dropdown', 'IncomingMailController@dropdownAjax')->name('incoming_mail_substorage');
			Route::get('/create', 'IncomingMailController@create')->name('incoming_mail_create');
			Route::post('/store', 'IncomingMailController@store')->name('incoming_mail_store');
			Route::get('/detail/{id?}', 'IncomingMailController@detail')->name('incoming_mail_detail');
			Route::get('/move/{id?}', 'IncomingMailController@move')->name('incoming_mail_move');
			Route::get('/edit/{id?}', 'IncomingMailController@edit')->name('incoming_mail_edit');
			Route::post('/update/{id?}', 'IncomingMailController@update')->name('incoming_mail_update');
			Route::post('/delete', 'IncomingMailController@delete')->name('incoming_mail_delete');
			Route::post('/dispotion', 'IncomingMailController@disposition')->name('incoming_mail_disposition');
		});

		//Outgoing Mail
		Route::group(['prefix' => 'keluar'], function(){
			Route::get('/', 'OutgoingMailController@index')->name('outgoing_mail');
		});
	});

	//Member
	Route::group(['prefix' => 'member', 'middleware' => MemberPermissions::class], function(){
		Route::get('/', 'MemberController@index')->name('member');
		Route::get('/create', 'MemberController@create')->name('member_create');
		Route::post('/store', 'MemberController@store')->name('member_store');
		Route::get('/edit/{id?}', 'MemberController@edit')->name('member_edit');
		Route::put('/update/{id?}', 'MemberController@update')->name('member_update');
		Route::post('/delete', 'MemberController@delete')->name('member_delete');
	});

	//Setting
	Route::group(['prefix' => 'setting'], function(){
		Route::get('/', 'SettingController@index')->name('setting');
		Route::post('/update/user', 'SettingController@updateuser')->name('update_user');
		Route::post('/update/company', 'SettingController@updatecompany')->name('update_company');
	});
});


