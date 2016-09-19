<?php namespace App\Http\Controllers;

class BaseController extends Controller {

	public $code;
	public $status;
	public $description;
	public $response;
	
	// We have to check if the token is valid here
	function check()
	{
		//return;
		// Log request

		$database = Config::get('database.connections.mysql');
				
		$dsn      = 'mysql:dbname='.$database['database'].';host='.$database['host'];
		$username = $database['username'];
		$password = $database['password'];
		
		// error reporting (this is a demo, after all!)
		ini_set('display_errors',1);error_reporting(E_ALL);
		
		// Autoloading (composer is preferred, but for this example let's just do this)
		//require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
		OAuth2\Autoloader::register();
		
		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
		
		// Pass a storage object or array of storage objects to the OAuth2 server class
		$server = new OAuth2\Server($storage);
	
		// Handle a request for an OAuth2.0 Access Token and send the response to the client
		if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
			$server->getResponse()->send();
			die;
		}
		
		//echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
	}

	public function log()
	{
		
	}
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}