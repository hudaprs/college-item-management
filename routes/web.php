<?php

if ((bool) env('HTTPS', false)) {
	\URL::forceScheme('https');
}

Route::get('/', function () {
	return redirect('/dev');
});

Route::group(['prefix' => 'dev'], function () {
	Route::get('/', 'HomeController@index');
	Route::get('/dev', 'HomeController@index');
	Route::get('users/datatables', 'Managements\DataTablesController@usersDataTables')->name('users.datatables');
	Route::get('users/datatables/trashed', 'Managements\DataTablesController@usersTrashedDataTables')->name('users.datatables.trashed');
	Route::resource('users', 'UserController');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::match(['POST', 'GET'], '/register', function () {
	return redirect('/');
});