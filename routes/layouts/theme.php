<?php
/*
|--------------------------------------------------------------------------
| Theme Routes
|--------------------------------------------------------------------------
|
| Here is where you can use theme routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// LOGIN / REGISTRATION
Auth::routes();
Route::post('/loginUser', 'Auth\LoginController@loginUser')->name('loginUser');
Route::post('/registerUser', 'Auth\RegisterController@register')->name('registerUser');
Route::get('/recover-password','Auth\RecoverPasswordController@recover_password')->name('recoverPassword');
Route::post('/recover-password','Auth\RecoverPasswordController@ajax_recover_password')->name('ajax.recover.password');

// FRONT
Route::get('/', 'HomeController@index')->name('firstPage');
Route::get('/run-croe', 'HomeController@runCroe')->name('runCroe');
Route::get('/trip', 'theme\MapController@index')->name('tripView');
Route::post('/roots', 'theme\MapController@ajax_roots')->name('roots');
Route::post('/stps', 'theme\MapController@ajax_stops')->name('stops');
Route::get('/track', 'theme\MapController@ticketValidate')->name('ticketValidate');
Route::get('/track/{ticket}', 'theme\MapController@track')->name('trackBusPage');
Route::get('/track-vehicles', 'theme\MapController@trackBackend')->name('trackBackend');
Route::get('/all-trigger-points/{page_id}', 'theme\MapController@mapTriggerPoints')->name('map.trigger-points');
Route::post('/trckbs', 'theme\MapController@ajax_track')->name('trackBus');
Route::post('/code/validate', 'theme\MapController@ajax_ticketValidate')->name('ajax.code.validate');
Route::post('/plcs', 'theme\MapController@ajax_places')->name('places');