<?php namespace App\Http\Controllers;
use BaseController, Input, Request, Response, Auth;
use App\Http\Controllers\Controller;
use Task;
use App\Transformers\TransformerManager;
use App\Transformers\TaskTransformer;

use WindowsAzure\Common\ServicesBuilder;
use League\Flysystem\Filesystem;
use League\Flysystem\Azure\AzureAdapter;

//use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Queue\Models\CreateQueueOptions;

use WindowsAzure\Queue\Models\CreateMessageOptions;//  for creating message

use WindowsAzure\Queue\Models\PeekMessagesOptions;

use PDO;

class ImportController extends Controller {
	
	protected $task;
	protected $transformerManager;
	
	public function __construct(Task $task, TransformerManager $transformerManager)
	{
		$this->middleware('auth.basic');
		$this->task 				= $task;
		$this->transformerManager 	= $transformerManager;
	}

	public function index()
	{
		

		$server = "tcp:qb06kvxrrd.database.windows.net";
		$user = "mannysoft";
		$pwd = "P0gi@k02007";
		$db = "dwellar";

		try{
		    $conn = new PDO( "dblib:Server= $server ; Database = $db ", $user, $pwd);
		    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch(Exception $e){
		    die(print_r($e));
		}

		return;
		// Steps:
		// We need to store the excel file to azure blob
		// Create a queue for processsing the excel file
		// Process the excel file
		// Insert to mobile services tables
		// Skip and log exceptions
		// Email reports

		
		$endpoint = sprintf(
		    'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
		    'dwellar',
		    'k7/PbL0OOoiwV+E7GDH8Q4Mm9cqGRJbRK2tgfl/MMZffGTCJcFXhQuQvf7gri45MeET8LJvmWkYKtPm3sANCEw=='
		);

		$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($endpoint);

		$filesystem = new Filesystem(new AzureAdapter($blobRestProxy, 'dwellar'));

		//$filesystem->update('path/to/file.txt', 'new contents');

		//$filesystem->delete('path/to/file.txt');

		//$filesystem->write('path/to/file.txt', 'contents');

		$connectionString = sprintf(
		    'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
		    'dwellar',
		    'k7/PbL0OOoiwV+E7GDH8Q4Mm9cqGRJbRK2tgfl/MMZffGTCJcFXhQuQvf7gri45MeET8LJvmWkYKtPm3sANCEw=='
		);

		$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

		$tableRestProxy->createTable("mytable");

		return;

		// Create queue REST proxy.
		$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);

		// OPTIONAL: Set queue metadata.
		$createQueueOptions = new CreateQueueOptions();
		$createQueueOptions->addMetaData("key1", "value1");
		$createQueueOptions->addMetaData("key2", "value2");

		try {
		    // Create queue.
		    $queueRestProxy->createQueue("myqueue", $createQueueOptions);
		}
		catch(ServiceException $e){
		    // Handle exception based on error codes and messages.
		    // Error codes and messages are here: 
		    // http://msdn.microsoft.com/library/azure/dd179446.aspx
		    $code = $e->getCode();
		    $error_message = $e->getMessage();
		    echo $code.": ".$error_message."<br />";
		}





		// Create queue REST proxy.
		$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);

		try {
		    // Create message.
		    $builder = new ServicesBuilder();
		    $queueRestProxy->createMessage("myqueue", "Hello World!123");
		}
		catch(ServiceException $e){
		    // Handle exception based on error codes and messages.
		    // Error codes and messages are here: 
		    // http://msdn.microsoft.com/library/azure/dd179446.aspx
		    $code = $e->getCode();
		    $error_message = $e->getMessage();
		    echo $code.": ".$error_message."<br />";
		}


		// Create queue REST proxy.
		$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);

		// OPTIONAL: Set peek message options.
		$message_options = new PeekMessagesOptions();
		$message_options->setNumberOfMessages(30); // Default value is 1.

		try {
		    $peekMessagesResult = $queueRestProxy->peekMessages("myqueue", $message_options);
		}
		catch(ServiceException $e){
		    // Handle exception based on error codes and messages.
		    // Error codes and messages are here: 
		    // http://msdn.microsoft.com/library/azure/dd179446.aspx
		    $code = $e->getCode();
		    $error_message = $e->getMessage();
		    echo $code.": ".$error_message."<br />";
		}

		$messages = $peekMessagesResult->getQueueMessages();

		// View messages.
		$messageCount = count($messages);
		if($messageCount <= 0){
		    echo "There are no messages.<br />";
		}
		else{
		    foreach($messages as $message)  {
		        echo "Peeked message:<br />";
		        echo "Message Id: ".$message->getMessageId()."<br />";
		        echo "Date: ".date_format($message->getInsertionDate(), 'Y-m-d')."<br />";
		        echo "Message text: ".$message->getMessageText()."<br /><br />";
		    }
		}



		return 'nice';

		$tasks = $this->task
				->where('user_id', '=', Auth::user()->id)
				->ordering()
				->paginate(50);	

		$data = $this->transformerManager
				->transform(
						$tasks, 
						new TaskTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

}
