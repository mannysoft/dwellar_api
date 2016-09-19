<?php namespace App\Http\Controllers;
use BaseController, Input, Request, Response, Auth;
use App\Http\Controllers\Controller;
use Task;
use App\Transformers\TransformerManager;
use App\Transformers\TaskTransformer;

class TaskController extends Controller {
	
	protected $task;
	protected $transformerManager;
	
	public function __construct(Task $task, TransformerManager $transformerManager)
	{
		$this->middleware('auth.basic');
		$this->task 				= $task;
		$this->transformerManager 	= $transformerManager;
	}

	public function create()
	{
		$task = $this->task->fill(Input::all());
		$task->user_id = Auth::user()->id;

		if($task->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($task->errors, 400);
	}

	public function show($id)
	{
		$task = $this->task->find($id);

		return $this->transformerManager
				->transform(
						$task, 
						new TaskTransformer, 
						'item'
						);

	}

	public function update($id)
	{
		$task = $this->task->find($id);

		// Lets return the old version of the task
		if($task->revision_number == Request::get('revision_number'))
		{
			return Response::json($task, 400);
		}

		$task->fill(Input::all());
			
		if($task->save())
		{
			return Response::json($task, 200);
		}

		return Response::json($task->errors, 400);
	}

	public function destroy($id)
	{
		$task = $this->task->find($id);

		if($task == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Task.'
				], 404); // 403
		}

		$task->delete();

		return Response::json(NULL, 200);
	}

	public function index()
	{
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

	public function search()
	{
		$tasks = $this->task
				->where('user_id', '=', Auth::user()->id)
				->where('task_name', 'LIKE', '%'.Input::get('q').'%')
				->orWhere('description', 'LIKE', '%'.Input::get('q').'%')
				->orderBy('created_at', 'DESC')
				->paginate(50);

		return $this->transformerManager
				->transform(
						$tasks, 
						new TaskTransformer, 
						'collection'
						);
	}	

}
