<?php 
class ServerApiController extends BaseController{

	function __construct()
	{
		//$this->check();
	}
	
	/**
	 * This functions give tokens
	 *
	 * @return Response
	 */
	public function index()
	{
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
		
		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		
		// Add the "Authorization Code" grant type (this is where the oauth magic happens)
		$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
		
		// Handle a request for an OAuth2.0 Access Token and send the response to the client
		$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
		
		//return 'server index';
	}

	public function checkToken()
	{
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
		$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());

		echo json_encode(array('success' => $token, 'message' => 'You accessed my APIs!'));

	}
	
}
?>
