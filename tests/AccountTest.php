<?php

class AccountTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		Route::enableFilters();
		Artisan::call('migrate');
		Auth::loginUsingId(1);
	}

	public function testMustBeAuthenticated()
	{
		Auth::logout();

		$response = $this->call('GET', 'api/v1/accounts');

		$this->assertEquals('Invalid credentials.', $response->getContent());
	}

	public function testFecthAllAccounts()
	{
		Auth::loginUsingId(1);

		$response = $this->call('GET', 'api/v1/accounts?page=1');
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('array', $data);

		// Verify that the array count is 1, not 2
		$this->assertCount(1, $data);
	}

	public function testCreateAccounts()
	{
		Auth::loginUsingId(1);

		// Test for adding new account
		$response = $this->call('POST', 'api/v1/accounts', [
					
					'email' 				=> 'mannysoft@gmail.com',
					'password' 				=> 'password',
					'otp' 					=> 'password',
					'first_name' 			=> 'Manny',
					'last_name' 			=> 'Isles',
					'account_timezone' 		=> 'GMT+8',
					'account_locale' 		=> 'EN',
					'state' 				=> 'active',
					'organization_name' 	=> 'Google Inc.',
					'organization_address' 	=> 'Las Vegas'
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);
	}

	public function testGetSingleAccounts()
	{
		Auth::loginUsingId(1);

		$response = $this->call('GET', 'api/v1/accounts/1');
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('object', $data);
	}

	public function testUpdateAccounts()
	{
		Auth::loginUsingId(1);

		// Test for adding new account
		$response = $this->call('PUT', 'api/v1/accounts/1', [
					
					'email' 				=> 'mannysoft@newmail.com',
					'first_name' 			=> 'Nikki',
					'last_name' 			=> 'Castillo',
					'organization_name' 	=> 'Microsoft',
					'organization_address' 	=> 'California',
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Did we receive valid JSON?
		$this->assertInternalType('object', $data);

		$this->assertEquals('mannysoft@newmail.com', $data->email);
	}

	public function testAccountNotFound()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/40/close', [
					'reason_code' => 1, 
					'reason_notes' => 'Bad'
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertFalse($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		$this->assertEquals(1, $data->error_code);
		$this->assertEquals('No such Account.', $data->error_description);
	}	

	public function testCloseAccount()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/1/close', [
					'reason_code' => 1, 
					'reason_notes' => 'Bad'
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('object', $data);
	}

	public function testReopenAccount()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/1/reopen', [
					'reason_code' => 2, 
					'reason_notes' => 'Nice Service'
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('object', $data);
	}

	public function testSuspendAccount()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/1/suspend', [
					'reason_code' => 3, 
					'reason_notes' => 'Bad User'
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('object', $data);
	}

	public function testInvalidCurrentPassword()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/1/password', [
					'current_password' 	=> 'taemo', // this is wrong password
					'new_password' 		=> 'Bad',
					'otp' 				=> 'Bad',
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertFalse($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		$this->assertEquals(2, $data->error_code);
		$this->assertEquals('You have enter an invalid current password', $data->error_description);
	}

	public function testChangePassword()
	{
		Auth::loginUsingId(1);

		$response = $this->call('PUT', 'api/v1/accounts/1/password', [
					'current_password' 	=> 'password',
					'new_password' 		=> 'goodnewpassword',
					'otp' 				=> 'goodnewpassword',
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		$this->assertInternalType('object', $data);
	}



}
