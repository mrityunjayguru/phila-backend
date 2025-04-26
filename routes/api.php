<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('customer')->group( function() {
    
	// CMS Pages
	Route::get('cms_pages', 'Api\Customer\CommonController@cmsPages');
	Route::get('get_general_info', 'Api\Customer\CommonController@general_information');
	Route::get('libitrack', 'Api\Customer\CommonController@libitrack');
    
	// COUTRTY, STATE, CITY LIST
	Route::get('get_countries', 'Api\Customer\CountryController@index');
    Route::get('get_state', 'Api\Customer\StateController@index');
    Route::get('get_cities', 'Api\Customer\CityController@index');
	
	// LANGUAGE
    Route::get('get_languages', 'Api\Customer\LanguageController@index');
	
	// Login, Registration, OTP
	Route::post('login', 'Api\Customer\Auth\AuthController@login');
    Route::post('registration', 'Api\Customer\Auth\AuthController@registration');
    Route::post('activeAccount', 'Api\Customer\Auth\AuthController@active');
    Route::post('sendOTP', 'Api\Customer\CommonController@sendOTP');
    Route::post('verifyOTP', 'Api\Customer\CommonController@verifyOTP');
    Route::post('forgot_password', 'Api\Customer\Auth\PasswordResetController@forgot_password');
    Route::post('reset_password', 'Api\Customer\Auth\PasswordResetController@reset_password');
    Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy-policy');
	//Slider
	Route::post('slider','Api\Customer\SliderController@index');
	
	// Dashboard
	Route::get('get_dashboard_data', 'Api\Customer\HomeController@dashboard');
	
	// OFFERS
	Route::post('offers', 'Api\Customer\OfferController@index');
	Route::get('offer/{id}', 'Api\Customer\OfferController@show');
	Route::get('offer_stops', 'Api\Customer\OfferController@stops');
	
	// PLACES
	Route::post('places', 'Api\Customer\PlaceController@index');
	Route::get('place/{id}', 'Api\Customer\PlaceController@show');
	
	// STOPS
	Route::post('stops', 'Api\Customer\StopController@index');
	Route::get('stop/{id}', 'Api\Customer\StopController@show');
	Route::post('routs', 'Api\Customer\StopController@routs');
	Route::post('map_routs', 'Api\Customer\StopController@mapRouts');
	
	// Track BUS
	Route::get('track-bus', 'Api\Customer\BusController@index');
	Route::post('track-bus', 'Api\Customer\BusController@index');
	Route::get('vehicle', 'Api\Customer\BusController@list');
	
	// FAQs
	Route::get('faqs', 'Api\Customer\FaqController@index');
	
	// Custom Map
	Route::get('custom-map', 'Api\Customer\SliderController@customMap');
	
	// Product, Category
	//Route::post('categories','Api\Customer\ProductController@categories');
	//Route::post('get_categoryProducts','Api\Customer\ProductController@categoryProducts');
	//Route::post('products','Api\Customer\ProductController@index');
	//Route::get('product/{id}','Api\Customer\ProductController@single');
	
	// NOTIFICATION
	Route::post('notifications','Api\Customer\NotificationController@list');
	Route::post('get_notification_settings','Api\Customer\NotificationController@getSettings');
	Route::post('update_firebase_token', 'Api\Customer\Auth\AuthController@updateToken');
	Route::post('update_notification_settings','Api\Customer\NotificationController@settings');

	// AUDIO
	
	Route::post('trigger-point', 'Api\Customer\AudioFileController@index')->name('trigger-point');
	// Route::post('create-audio', 'Api\Customer\AudioFileController@save')->name('create-audio');
	// Route::post('delete-audio','Api\Customer\AudioFileController@delete')->name('delete-audio');
	Route::post('trigger-point-view', 'Api\Customer\AudioFileController@show')->name('trigger-point-view');


	Route::post('/update-gps-validation', 'Api\Customer\AudioFileController@updateGps')->name('gps.update');
	// SAMPLE AUDIO
	
	Route::get('get-landing-page', 'Api\Customer\SampleAudioController@getLandingPage');
	Route::post('get-sample-audio-language', 'Api\Customer\SampleAudioController@index')->name('get-sample-audio-language');
	// Route::post('create-audio', 'Api\Customer\AudioFileController@save')->name('create-audio');
	// Route::post('delete-audio','Api\Customer\AudioFileController@delete')->name('delete-audio');
	Route::post('show-audio', 'Api\Customer\SampleAudioController@show')->name('show-audio');
	Route::get('get-active-status-language', 'Api\Customer\SampleAudioController@getSampleAudioStatusActive')->name('get-active-status-language');
	
    Route::middleware(['auth:api'])->group( function () {
		// Auth
		Route::get('logout', 'Api\Customer\Auth\AuthController@logout');
		
		//USER
		Route::get('get_myProfile', 'Api\Customer\UserController@profile');
		Route::post('updateProfile', 'Api\Customer\UserController@update');
    });
});