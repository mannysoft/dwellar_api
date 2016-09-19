<?php namespace App\Http\Controllers;
use BaseController, Input, Request, Response, Auth;
use App\Http\Controllers\Controller;
use Contact, PhotoUploader;
use App\Transformers\TransformerManager;
use App\Transformers\ContactTransformer;

use Storage, File;

class ContactController extends Controller {
	
	protected $contact;
	protected $transformerManager;
	protected $photoUploader;
	
	public function __construct(Contact $contact, TransformerManager $transformerManager, PhotoUploader $photoUploader)
	{
		$this->middleware('auth.basic');
		$this->contact 				= $contact;
		$this->transformerManager 	= $transformerManager;
		$this->photoUploader 		= $photoUploader;
	}

	public function create()
	{	
		$contact = $this->contact->fill(Input::all());
		$contact->birth_date = Input::get('birth_date');
		$contact->user_id = Auth::user()->id;
		$contact->mobile = Input::get('mobile');
		$contact->email = Input::get('email');

		$this->photoUploader->setPath('contacts/'.Auth::user()->id);

		$photo = $this->photoUploader->upload();

		if($photo != NULL)
		{
			$contact->photo 	= $photo->filename;
			$contact->mime 		= $photo->mime;
		}
		
		if($contact->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($contact->errors, 400);
	}

	public function show($id)
	{
		$account = $this->contact->find($id);

		return $this->transformerManager
				->transform(
						$account, 
						new ContactTransformer, 
						'item'
						);

	}

	public function photo($id)
	{
		$contact = $this->contact->find($id);

		$file = 'contacts/'.$contact->user_id.'/'.$contact->photo;

		$fileExists = Storage::exists($file);

		if($fileExists)
		{
			$file = Storage::get($file);

			$mime = 'image/jpeg';

			if($contact->mime != '')
			{
				$mime = $contact->mime;
			}

			return Response($file, 200)->header('Content-Type', $mime);
		}

	}

	public function update($id)
	{
		$contact = $this->contact->find($id);

		// Lets return the old version of the contact
		if($contact->revision_number == Request::get('revision_number'))
		{
			return Response::json($contact, 400);
		}

		$contact->fill(Input::all());
		$contact->birth_date = Input::get('birth_date');
		$contact->user_id = Auth::user()->id;
		$contact->mobile = Input::get('mobile');
		$contact->email = Input::get('email');

		$contact->photo = $this->photoUploader->upload();
			
		if($contact->save())
		{
			return Response::json($contact, 200);
		}

		return Response::json($contact->errors, 400);
	}

	public function destroy($id)
	{
		$account = $this->contact->find($id);

		if($account == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Contact.'
				], 404); // 403
		}

		$account->delete();

		return Response::json(NULL, 200);
	}

	public function index()
	{
		$contacts = $this->contact
				->where('user_id', '=', Auth::user()->id)
				->orderBy('name')
				->paginate(50);	

		$data = $this->transformerManager
				->transform(
						$contacts, 
						new ContactTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function search()
	{
		$contacts = $this->contact
				->where('user_id', '=', Auth::user()->id)
				->where('name', 'LIKE', '%'.Input::get('q').'%')
				->orderBy('name')
				->paginate(50);

		return $this->transformerManager
				->transform(
						$contacts, 
						new ContactTransformer, 
						'collection'
						);
	}	

}
