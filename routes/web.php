<?php

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
	});

	//Company
	Route::group(['prefix' => 'company'], function(){
		Route::get('/', 'CompanyController@index')->name('company');
		Route::put('/update/{id?}', 'CompanyController@update')->name('company_update');
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
	Route::group(['prefix' => 'storage'], function(){
		Route::get('/', 'StorageController@index')->name('storage');
		Route::get('/register', 'StorageController@register')->name('storage_register');
		Route::get('/success', 'StorageController@success')->name('storage_register_success');
		Route::get('/create', 'StorageController@create')->name('storage_create');
		Route::post('/store', 'StorageController@store')->name('storage_store');
		Route::get('/edit/{id?}', 'StorageController@edit')->name('storage_edit');
		Route::get('/edit/{id?}', 'StorageController@edit')->name('storage_edit');
		Route::put('/update/{id?}', 'StorageController@update')->name('storage_update');
		Route::post('/delete', 'StorageController@delete')->name('storage_delete');
	});

	//Storage-Sub
	Route::group(['prefix' => 'storage/sub'], function(){
		Route::get('/register', 'StorageSubController@register')->name('storage_sub_register');
		Route::post('/register/store', 'StorageSubController@registerstore')->name('storage_sub_register_store');
		Route::get('/{id?}', 'StorageSubController@index')->name('storage_sub');
		Route::get('/{storage?}/create', 'StorageSubController@create')->name('storage_sub_create');
		Route::post('/store', 'StorageSubController@store')->name('storage_sub_store');
		Route::get('/edit/{id?}', 'StorageSubController@edit')->name('storage_sub_edit');
		Route::put('/update/{id?}', 'StorageSubController@update')->name('storage_sub_update');
		Route::post('/delete', 'StorageSubController@delete')->name('storage_sub_delete');
	});

	//Dashboard
	Route::group(['prefix' => 'dashboard'], function(){
		Route::get('/', 'DashboardController@index')->name('dashboard');
	});

	//Surat
	Route::group(['prefix' => 'surat'], function(){
		//Incoming Mail
		Route::group(['prefix' => 'masuk'], function(){
			Route::get('/', 'IncomingMailController@index')->name('incoming_mail');
			Route::post('/upload', 'IncomingMailController@upload')->name('incoming_mail_upload');
			Route::post('/upload/ajax', 'IncomingMailController@uploadAjax')->name('incoming_mail_upload_ajax');
			Route::get('/create', 'IncomingMailController@create')->name('incoming_mail_create');
			Route::post('/store', 'IncomingMailController@store')->name('incoming_mail_store');
			Route::get('/detail/{id?}', 'IncomingMailController@detail')->name('incoming_mail_detail');
			Route::post('/edit/{id?}', 'IncomingMailController@edit')->name('incoming_mail_edit');
			Route::post('/update', 'IncomingMailController@update')->name('incoming_mail_update');
			Route::get('/delete/{id?}', 'IncomingMailController@delete')->name('incoming_mail_delete');
			Route::get('/restore/{id?}', 'IncomingMailController@restore')->name('incoming_mail_restore');
		});
	});

	//Member
	Route::group(['prefix' => 'member'], function(){
		Route::get('/', 'MemberController@index')->name('member');
		Route::get('/create', 'MemberController@create')->name('member_create');
		Route::post('/store', 'MemberController@store')->name('member_store');
		Route::get('/edit/{id?}', 'MemberController@edit')->name('member_edit');
		Route::put('/update/{id?}', 'MemberController@update')->name('member_update');
		Route::post('/delete', 'MemberController@delete')->name('member_delete');
	});
});


