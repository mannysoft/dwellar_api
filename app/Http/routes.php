<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

get('/', function(){

	return 'API v1';
});

Route::get('docs', ['middleware'=>'auth.basic', 'uses' => 'PropertyController@docs']);

// http://bshaffer.github.io/oauth2-server-php-docs/cookbook/
// Request for access_token
Route::any('server', 'ServerApiController@index'); //  call a curl curl -u testclient:testpass http://localhost:8000/server -d 'grant_type=client_credentials'
Route::any('check', 'ServerApiController@checkToken'); // check token curl http://localhost:8000/check -d 'access_token=YOUR_TOKEN'

// We have to create filter for logging and rate limit

Route::group(['prefix' => 'api/v1', 'before' => 'log-calls'], function()
{
	
	Route::group(['prefix' => 'accounts'], function()
	{
		post('/', 'AccountController@create');
		get('/{id}', 'AccountController@show');
		put('/{id}', 'AccountController@update');
		put('/{id}/close', 'AccountController@closeAccount');
		put('/{id}/reopen', 'AccountController@reopenAccount');
		put('/{id}/suspend', 'AccountController@suspendAccount');
		get('/', 'AccountController@index');
		put('/{id}/password', 'AccountController@passwordAccount');

	});

	Route::group(['prefix' => 'contacts'], function()
	{
		get('/', 'ContactController@index');
		get('search', 'ContactController@search');
		get('/{id}', 'ContactController@show');
		get('/{id}/photo', 'ContactController@photo');
		post('/', 'ContactController@create');
		put('/{id}', 'ContactController@update');
		delete('/{id}', 'ContactController@destroy');

	});

	// Properties
	Route::group(['prefix' => 'properties'], function()
	{
		get('/', 'PropertyController@index');
		get('/{id}', 'PropertyController@show');
		get('/{id}/photo/{image_id}', 'PropertyController@photo');
		post('/{id}/import', 'PropertyController@import');
		post('/', 'PropertyController@create');
		delete('/{id}', 'PropertyController@destroy');

		post('/{id}/imageupload', 'PropertyController@uploadImage');

		// compensations
		get('/{id}/compensations', 'PropertyController@compensations');
		post('/{id}/compensations', 'PropertyController@createCompensation');
		put('/{id}/compensations/{compensation_id}', 'PropertyController@updateCompensation');
		delete('/{id}/compensations/{compensation_id}', 'PropertyController@destroyCompensation');

		// charges
		get('/{id}/charges', 'PropertyController@charges');
		post('/{id}/charges', 'PropertyController@createCharge');
		put('/{id}/charges/{charge_id}', 'PropertyController@updateCharge');
		delete('/{id}/charges/{charge_id}', 'PropertyController@destroyCharge');

		// communities
		get('/{id}/communities', 'PropertyController@communities');
		post('/{id}/communities', 'PropertyController@createCommunity');
		put('/{id}/communities/{community_id}', 'PropertyController@updateCommunity');
		delete('/{id}/communities/{community_id}', 'PropertyController@destroyCommunity');

		// notes
		get('/{id}/notes', 'PropertyController@notes');
		post('/{id}/notes', 'PropertyController@createNote');
		put('/{id}/notes/{note_id}', 'PropertyController@updateNote');
		delete('/{id}/notes/{note_id}', 'PropertyController@destroyNote');

		// Offers
		get('/{id}/offers', 'PropertyController@offers');
		post('/{id}/offers/{offer_id}/accept', 'PropertyController@acceptOffer');
		post('/{id}/offers/{offer_id}/decline', 'PropertyController@declineOffer');

	});

	// Tasks
	Route::group(['prefix' => 'tasks'], function()
	{
		get('/', 'TaskController@index');
		get('search', 'TaskController@search');
		get('/{id}', 'TaskController@show');
		post('/', 'TaskController@create');
		put('/{id}', 'TaskController@update');
		delete('/{id}', 'TaskController@destroy');

	});

});