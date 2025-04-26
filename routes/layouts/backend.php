<?php
/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can use Backend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// BACKEND
Route::group(['middleware' => ['auth'],'prefix' => 'backend','namespace' => 'Backend'], function() {
	Route::get('/','HomeController@index')->name('homePage');
	Route::get('/dashboard','HomeController@index')->name('dashboard');
	
	// Auth
	Route::get('/change-password','ProfileController@change_password')->name('change_password');
	Route::post('/change-password','ProfileController@ajax_change_password')->name('changePassword');
	
	// Tickets 
	Route::resource('tickets', 'TicketController');
	Route::get('/ticket/{id}','TicketController@index')->name('ticket.fetch');
	Route::post('/ticket-list','TicketController@ajax_list')->name('ajax.ticket.list');
	Route::post('/ticket/status','TicketController@change_status')->name('ajax.change.ticket.status');
	Route::post('/delete-ticket','TicketController@destroy')->name('ajax.delete.ticket');
	
	// Sliders 
	Route::resource('sliders', 'SliderController');
	Route::post('/slider/slidebox','SliderController@slideBox')->name('ajax.slider.open.slide.box');
	Route::post('/slider-list','SliderController@ajax_list')->name('ajax.slider.list');
	Route::post('/delete-slider','SliderController@destroy')->name('ajax.delete.slider');
	Route::post('/store-slide','SliderController@storeSlide')->name('ajax.slider.store.slide');
	Route::post('/delete-slide','SliderController@destroy_slide')->name('ajax.delete.slide');
	
	// LOCATION
	Route::resource('countries', 'locations\CountryController');
	Route::post('/countries/ajax','locations\CountryController@ajax_list')->name('ajax.country.list');
	Route::post('/Country/status','locations\CountryController@change_status')->name('ajax.change.country.status');
	
	// Area
	Route::resource('areas', 'locations\AreaController');
	Route::post('/areas/ajax','locations\AreaController@ajax_list')->name('ajax.area.list');
	Route::post('/areas/status','locations\AreaController@change_status')->name('ajax.change.area.status');
	
	// Places
	Route::get('places/{type}', 'PlaceController@index')->name('page.places');
	Route::get('places/{type}/add', 'PlaceController@create')->name('places.create');
	Route::get('places/{type}/{id}', 'PlaceController@edit')->name('ajax.place.edit');
	Route::post('places/store', 'PlaceController@store')->name('ajax.place.store');
	Route::post('places/type/ajax', 'PlaceController@ajax_list')->name('ajax.place.list');
	Route::post('places/status', 'PlaceController@change_status')->name('ajax.place.change.status');
	Route::post('delete-place', 'PlaceController@destroy')->name('ajax.place.delete');
	Route::post('/place-remove-image','PlaceController@removePlaceImage')->name('place.remove_image');
	
	// STOPS 
	Route::resource('stops', 'StopController');
	Route::post('/stop-list','StopController@ajax_list')->name('ajax.stop.list');
	Route::post('stop/status', 'StopController@change_status')->name('ajax.stop.change.status');
	Route::post('/delete-stop','StopController@destroy')->name('ajax.delete.stop');
	Route::post('/stop-remove-image','StopController@removeStopImage')->name('stops.remove_image');
	Route::post('/stop-remove-stop-image','StopController@removeStopStopImage')->name('stop.remove_stop_image');
	
	// Distance Calculate
	Route::get('/distance/{stop}','DistanceController@distance')->name('page.distance');
	Route::get('/calculate/{stop1}/{stop2}','DistanceController@calculate')->name('page.distance.calculate');
	
	// BUS
	Route::resource('buses', 'BusController');
	Route::post('/buses-list','BusController@ajax_list')->name('ajax.buses.list');
	Route::post('stop/status', 'BusController@change_status')->name('ajax.bus.change.status');
	Route::post('stop/security', 'BusController@change_security')->name('ajax.bus.change.security');
	Route::post('/delete-bus','BusController@destroy')->name('ajax.delete.bus');
	Route::post('stop/audio_status', 'BusController@change_audio_status')->name('ajax.audio.change.audio.status');

	// Timing
	Route::resource('timings', 'TimingController');
	Route::post('/timing/ajax','TimingController@store')->name('ajax.timing.store');
	
	
	// About Us
	Route::resource('about', 'AboutController');
	Route::post('/about/store','AboutController@store')->name('ajax.about.store');
	
	// FAQs
	Route::resource('faqs', 'FaqController');
	Route::post('/faq/list','FaqController@ajax_list')->name('ajax.faq.list');
	Route::post('/faq/store','FaqController@store')->name('ajax.faq.store');
	Route::post('/faq/delete','FaqController@destroy')->name('ajax.delete.faq');
	
	// FAQ Topics
	Route::resource('faq-topics', 'FaqTopicController');
	Route::post('/faq-topic/list','FaqTopicController@ajax_list')->name('ajax.faq-topic.list');
	Route::post('/faq-topic/store','FaqTopicController@store')->name('ajax.faq-topic.store');
	Route::post('/faq-topic/delete','FaqTopicController@destroy')->name('ajax.delete.faq-topic');
	
	// Custom Map
	Route::resource('custom-map', 'CustomMapController');
	Route::post('/custom-map/ajax','CustomMapController@store')->name('ajax.customMap.store');
	Route::post('/custom-map-remove-image','CustomMapController@removeImage')->name('ajax.custom-map.remove-image');
	
	// Notification
	Route::resource('send-notification', 'NotificationController');
	Route::post('/fire-notification','NotificationController@fire')->name('ajax.notification.fire');
	
	// Offer
	Route::resource('offers', 'OfferController');
	Route::post('/offer/ajax','OfferController@ajax')->name('offer_list');
	Route::post('/delete_offer','OfferController@destroy')->name('delete_offer');
	
	// Users (Roles Wise Management)
	Route::get('manage/{role}', 'UserManagementController@index')->name('user.management');
	Route::get('manage/{role}/add', 'UserManagementController@create')->name('user.management.add');
	Route::get('manage/{role}/{id}', 'UserManagementController@show')->name('user.management.edit');
	Route::post('manage/store', 'UserManagementController@store')->name('user.management.store');
	Route::post('manage-user/ajax', 'UserManagementController@ajax_list')->name('ajax.user.management.list');
	Route::post('manage-user/permissions', 'UserManagementController@permissions')->name('ajax.user.permissions.list');
	Route::post('manage-user/status', 'UserManagementController@change_status')->name('ajax.user.change.status');
	Route::post('manage-user/delete','UserManagementController@destroy')->name('ajax.user.management.delete');
	
	// SETTINGS
	Route::get('/general-settings','SettingController@general_settings')->name('general-settings');
	Route::post('/general-setting/store','SettingController@store')->name('ajax.store.general-settings');
	Route::post('/general-setting/store-logo','SettingController@store_logo')->name('ajax.store.logo');
	Route::get('/paymentGateways','PaymentGatewayController@index')->name('paymentGateways');
	Route::post('/paymentGateway/list','PaymentGatewayController@ajax')->name('ajax.paymentGateway.list');
	Route::post('/paymentGateway/edit','PaymentGatewayController@ajax')->name('paymentGateway.edit');
	Route::post('/paymentGateway/status','PaymentGatewayController@change_status')->name('change.paymentGateway.status');

	// Sample Audio
	// Route::get('sample-audio', 'SampleAudioController@index')->name('sample-audio');
	Route::get('sample-audio/{page_id}', 'SampleAudioController@index')->name('sample-audio');
	Route::get('sample-audio-create', 'SampleAudioController@create')->name('sample-audio-create');
	Route::post('sample-audio-store', 'SampleAudioController@store')->name('sample-audio-store');
	Route::post('sample-audio-list','SampleAudioController@ajax_list')->name('sample-audio-list');
	Route::post('audio/status', 'SampleAudioController@change_status')->name('ajax.audio.change.status');
	Route::post('/sample-audio-delete','SampleAudioController@destroy')->name('sample-audio-delete');
	Route::get('edit-sample-audio/{page_id}/{id}','SampleAudioController@edit')->name('edit-sample-audio');
	Route::post('update-sample-audio', 'SampleAudioController@update')->name('update-sample-audio');

	// Audio
	Route::get('audio/{page_id}', 'AudioFileController@index')->name('audio');
	Route::get('audio-create/{page_id}', 'AudioFileController@create')->name('audio-create');
	Route::post('audio-store', 'AudioFileController@store')->name('audio-store');
	Route::post('audio-list','AudioFileController@ajax_list')->name('audio-list');
	Route::post('audio/status', 'AudioFileController@change_status')->name('ajax.audio.change.status');
	Route::post('/delete-audio','AudioFileController@destroy')->name('delete-audio');
	Route::get('edit-audio/{page_id}/{id}','AudioFileController@edit')->name('edit-audio');
	// Route::post('update-audio/{id}', 'AudioFileController@update')->name('update-audio');
	Route::post('update-audio', 'AudioFileController@update')->name('update-audio');
	Route::post('/audio-remove-image','AudioFileController@removeAudioImage')->name('audio.remove_image');

   Route::get('/gps-validation', 'AudioFileController@getGpsValidationStatus')->name('gps.get');
   Route::post('/update-gps-validation', 'AudioFileController@updateGps')->name('gps.update');

	// LANDING PAGE
	Route::get('landing-page', 'LandingPageController@index')->name('landing-page');
	Route::get('page-create', 'LandingPageController@create')->name('page-create');
	Route::post('page-store', 'LandingPageController@store')->name('page-store');
	Route::post('page-list','LandingPageController@ajax_list')->name('page-list');
	Route::post('page/status', 'LandingPageController@change_status')->name('ajax.page.change.status');
	Route::post('/delete-page','LandingPageController@destroy')->name('delete-page');
	Route::get('edit-page/{id}','LandingPageController@edit')->name('edit-page');
	Route::post('update-page', 'LandingPageController@update')->name('update-page');

	// Audio Code 
	// Route::resource('codes', 'AudioCodeController');

	Route::get('/codes/{page_id}','AudioCodeController@index')->name('codes.index');
	Route::get('/codes/create/{page_id}','AudioCodeController@create')->name('codes.create');
	Route::get('/codes/edit/{page_id}/{id}','AudioCodeController@edit')->name('codes.edit');
	Route::post('/codes/store','AudioCodeController@store')->name('codes.store');
	Route::post('/codes-list','AudioCodeController@ajax_list')->name('ajax.codes.list');
	Route::post('/delete-code','AudioCodeController@destroy')->name('ajax.delete.code');
	
});