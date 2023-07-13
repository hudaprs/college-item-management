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
	Route::resource('users', 'UserController');
	Route::resource('products', 'ProductController');
	Route::prefix('transactions')->group(function () {
		Route::get('/', 'TransactionController@index')->name('transactions.index');
		Route::post('/', 'TransactionController@store')->name('transactions.store');
		Route::get('{id}', 'TransactionController@show')->name('transactions.show');
	});
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::match(['POST', 'GET'], '/register', function () {
	return redirect('/');
});