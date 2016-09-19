<?php

class ContactTest extends TestCase {

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

		$response = $this->call('GET', 'api/v1/contacts');

		$this->assertEquals('Invalid credentials.', $response->getContent());
	}
	
	public function testCreateContact()
	{
		Auth::loginUsingId(1);

		// Test for adding new account
		$response = $this->call('POST', 'api/v1/contacts', [
					
					
					'user_id'		=> Auth::user()->id,
					'name' 			=> 'Manny Isles',
					'year' 			=> '1995',
					'month' 			=> '02',
					'day' 			=> '06',
					'address' 			=> '1st st',
					'city' 			=> 'Orlando',
					'state' 			=> 'Florida',
					'country' 			=> 'USA',
					'zip' 			=> '10009',
					'mobile[]' 			=> '1995',
					'mobile[]' 			=> '1995',
					'email[]' 			=> 'max@y.com',
					'email[]' 			=> 'men@u.com',
					'tags' 			=> 'maxyne, pa, tell, story',
					'description' 			=> '1995',
					'fb' 			=> 'fb.com/mannyisles',
					'twitter' 			=> 'twitter.com/mannysoft',
					'google' 			=> 'plus.google.com/mannyisles',
					'revision_number' => 1,
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);
	}

	public function testFecthAllContacts()
	{
		Auth::loginUsingId(1);

		$response = $this->call('GET', 'api/v1/contacts?page=1');
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('array', $data);

		// Verify that the array count is 1, not 2
		$this->assertCount(1, $data);
	}

	public function testGetSingleContact()
	{
		Auth::loginUsingId(1);

		$response = $this->call('GET', 'api/v1/contacts/1');
		$content = $response->getContent();
		$data = json_decode($content);

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Decoded JSON should offer an array
		$this->assertInternalType('object', $data);
	}
	
	public function testUpdateContacts()
	{
		Auth::loginUsingId(1);

		// Test for adding new account
		$response = $this->call('POST', 'api/v1/contacts/1', [
					
					'name' 			=> 'Cool Guy',
					'year' 			=> '1995',
					'month' 			=> '02',
					'day' 			=> '06',
					'address' 			=> '1st st',
					'city' 			=> 'Orlando',
					'state' 			=> 'Florida',
					'country' 			=> 'Philippines',
					'zip' 			=> '10009',
					'mobile[]' 			=> '1995',
					'mobile[]' 			=> '1995',
					'email[]' 			=> 'max@y.com',
					'email[]' 			=> 'men@u.com',
					'tags' 			=> 'maxyne, pa, tell, story',
					'description' 			=> '1995',
					'fb' 			=> 'fb.com/mannyisles',
					'twitter' 			=> 'twitter.com/mannysoft',
					'google' 			=> 'plus.google.com/mannyisles',
					'revision_number' => 2
					]
		);
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertTrue($response->isOk());

		// Did we receive valid JSON?
		$this->assertJson($content);

		// Did we receive valid JSON?
		$this->assertInternalType('object', $data);

		$this->assertEquals('Cool Guy', $data->name);
		$this->assertEquals('Philippines', $data->country);
		
	}

	public function testDeleteContactNotFound()
	{
		Auth::loginUsingId(1);

		$response = $this->call('DELETE', 'api/v1/contacts/40');
		$content = $response->getContent();
		$data = json_decode($content);

		$this->assertFalse($response->isOk());

		$this->assertEquals(1, $data->error_code);
		$this->assertEquals('No such Contact.', $data->error_description);
	}	
	
	// to do::
	public function testContactSearch()
	{
		Auth::loginUsingId(1);

		$this->assertEquals(1, 1);
	}


}
