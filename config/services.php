<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'User',
		'secret' => '',
	],

	'facebook' => [
	    'client_id' => '794074777331970',
	    'client_secret' => 'd06ae0b4280bfac08131d12a1a8d6300',
	    'redirect' => 'http://api.elanic.in/api/v1/callback',
	],

	'google' => [
	    'client_id' => '1024447012260-n8ujmqndl832bg09qf76hbqqku1n99ba.apps.googleusercontent.com',
	    'client_secret' => 'KmhxXYlqoX2JbrfDI7OjUXPi',
	    'redirect' => 'http://api.elanic.in/api/v1/callback',
	],

];
