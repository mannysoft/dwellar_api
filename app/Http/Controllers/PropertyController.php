<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use BaseController, Input, Response, Auth, Request, Session;
use Excel;
use Property;
use PropertyCompensation;
use PropertyLegislativeCharge;
use PropertyCommunity;
use PropertyNote;
use PropertyOffer;

use PropertyImage;

use PhotoUploader;
use App\Transformers\TransformerManager;

use App\Transformers\PropertyTransformer;
use App\Transformers\PropertyCompensationTransformer;
use App\Transformers\PropertyChargeTransformer;
use App\Transformers\PropertyCommunityTransformer;
use App\Transformers\PropertyNoteTransformer;
use App\Transformers\PropertyOfferTransformer;

use DB;

use Storage;
class PropertyController extends Controller {
	
	protected $property;
	protected $propertyCompensation;
	protected $propertyLegislativeCharge;
	protected $propertyCommunity;
	protected $propertyNote;

	protected $propertyCompensationTransformer;
	protected $propertyChargeTransformer;
	protected $transformerManager;
	protected $photoUploader;
	
	public function __construct(
				TransformerManager $transformerManager, 
				PhotoUploader $photoUploader,

				Property $property,
				PropertyCompensation $propertyCompensation,
				PropertyLegislativeCharge $propertyLegislativeCharge,
				PropertyCommunity $propertyCommunity,
				PropertyNote $propertyNote,
				PropertyOffer $propertyOffer,

				PropertyImage $propertyImage,

				PropertyTransformer $propertyTransformer,
				PropertyCompensationTransformer $propertyCompensationTransformer,
				PropertyChargeTransformer $propertyChargeTransformer,
				PropertyCommunityTransformer $propertyCommunityTransformer,
				PropertyNoteTransformer $propertyNoteTransformer
				)
	{
		$this->middleware('auth.basic');
		
		$this->transformerManager 			= $transformerManager;
		$this->photoUploader 				= $photoUploader;

		$this->property 					= $property;
		$this->propertyCompensation 		= $propertyCompensation;
		$this->propertyLegislativeCharge 	= $propertyLegislativeCharge;
		$this->propertyCommunity 			= $propertyCommunity;
		$this->propertyNote 				= $propertyNote;
		$this->propertyOffer 				= $propertyOffer;
		$this->propertyImage 				= $propertyImage;

		
		$this->propertyTransformer  		= $propertyTransformer;
		$this->propertyCompensationTransformer = $propertyCompensationTransformer;
		$this->propertyChargeTransformer 	= $propertyChargeTransformer;
		$this->propertyCommunityTransformer = $propertyCommunityTransformer;
		$this->propertyNoteTransformer		= $propertyNoteTransformer;
		//$this->propertyOfferTransformer		= $propertyOfferTransformer;

		
	}

	public function uploadImage($id)
	{
		$this->photoUploader->setPath('properties/'.$id);

		$photo = $this->photoUploader->upload();

		if($photo != NULL)
		{
			$image = new PropertyImage;
			$image->property_id = $id;
			$image->filename 	= $photo->filename;
			$image->mime 		= $photo->mime;
			$image->save();

		}

	
		if (Session::has('photos'))
		{
		    //$photos = Session::get('photos');
		    //
		}

		
	}	

	public function create()
	{
		//return Input::json()->get('test');
		$property = $this->property->fill(Input::all());
		$property->user_id = Auth::user()->id;

		//$photos = 

		//return $property;

		//$property->photo = $this->photoUploader->upload();

		if($property->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($property->errors, 400);
	}

	public function show($id)
	{
		$property = $this->property->find($id);

		return $this->transformerManager
				->transform(
						$property, 
						$this->propertyTransformer,
						'item'
						);

	}

	public function photo($id, $imageId)
	{
		$image = $this->propertyImage->find($imageId);

		if($image == null) return;

		$file = 'properties/'.$image->property_id.'/'.$image->filename;

		$fileExists = Storage::exists($file);

		if($fileExists)
		{
			$file = Storage::get($file);

			$mime = 'image/jpeg';

			if($image->mime != '')
			{
				$mime = $image->mime;
			}

			return Response($file, 200)->header('Content-Type', $mime);
		}

	}

	public function import($id)
	{
		

	}

	public function update($id)
	{
		$contact = $this->contact->find($id);

		// Lets return the old version of the contact
		if($contact->revision_number == Input::get('revision_number'))
		{
			return Response::json($contact, 400);
		}

		$contact->fill(Input::all());

		$contact->photo = $this->photoUploader->upload();
			
		if($contact->save())
		{
			return Response::json($contact, 200);
		}

		return Response::json($contact->errors, 400);
	}

	public function destroy($id)
	{
		//return DB::table('properties')->where('id', '=', '50003')->get();
		//return $this->property->traitDelete($id);
		//return $id;
		//return $this->property->where('id', '=', $id)->count();
		$property = $this->property->find($id);
		 //var_dump($property); return '';

		if($property == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Property.'
				], 404); // 403
		}

		$property->delete();

		return Response::json(NULL, 200);

		return $this->property->traitDelete($id);
	}

	public function index()
	{
		$property = $this->property
				//->where('user_id', '=', Auth::user()->id)
				->with('images', 'charges', 'offers', 'notes', 'compensations', 'communities')
				->paginate(50);	

		$data = $this->transformerManager
				->transform(
						$property, 
						new PropertyTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function compensations($propertyId)
	{
		$property = $this->propertyCompensation
					->where('property_id', '=', $propertyId)
					->get();	

		$data = $this->transformerManager
				->transform(
						$property, 
						new PropertyCompensationTransformer, 
						'collection'
						);

		return Response::json($data, 200);
	}

	public function createCompensation($propertyId)
	{
		$compensation = $this->propertyCompensation->fill(Input::all());
		$compensation->property_id = $propertyId;

		if($compensation->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($compensation->errors, 400);
	}

	public function updateCompensation($propertyId, $compensationId)
	{
		$compensation = $this->propertyCompensation->find($compensationId);
		$compensation->fill(Input::all());

		if($compensation->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($compensation->errors, 400);
	}

	public function destroyCompensation($propertyId, $compensationId)
	{
		$compensations = $this->propertyCompensation->find($compensationId);

		if($compensations == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Compensation.'
				], 404); // 403
		}

		$compensations->delete();

		return Response::json(NULL, 200);
	}

	public function charges($propertyId)
	{
		$charges = $this->propertyLegislativeCharge
					->where('property_id', '=', $propertyId)
					->get();	

		$data = $this->transformerManager
				->transform(
						$charges, 
						new PropertyChargeTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function createCharge($propertyId)
	{
		$charges = $this->propertyLegislativeCharge->fill(Input::all());
		$charges->property_id = $propertyId;

		if($charges->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($charges->errors, 400);
	}

	public function updateCharge($propertyId, $chargeId)
	{
		$charges = $this->propertyLegislativeCharge->find($chargeId);
		$charges->fill(Input::all());

		if($charges->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($charges->errors, 400);
	}

	public function destroyCharge($id)
	{
		$charges = $this->propertyLegislativeCharge->find($id);

		if($charges == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Charges.'
				], 404); // 403
		}

		$charges->delete();

		return Response::json(NULL, 200);
	}

	public function communities($propertyId)
	{
		$property = $this->propertyCommunity
					->where('property_id', '=', $propertyId)
					->get();	

		$data = $this->transformerManager
				->transform(
						$property, 
						new PropertyCommunityTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function createCommunity($propertyId)
	{
		$communities = $this->propertyCommunity->fill(Input::all());
		$communities->property_id = $propertyId;

		if($communities->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($communities->errors, 400);
	}

	public function updateCommunity($propertyId, $communityId)
	{
		$communities = $this->propertyCommunity->find($communityId);
		$communities->fill(Input::all());

		if($communities->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($communities->errors, 400);
	}

	public function destroyCommunity($id)
	{
		$compensations = $this->propertyCommunity->find($id);

		if($compensations == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Community.'
				], 404); // 403
		}

		$compensations->delete();

		return Response::json(NULL, 200);
	}

	public function notes($propertyId)
	{
		$property = $this->propertyNote
					->where('property_id', '=', $propertyId)
					->get();	

		$data = $this->transformerManager
				->transform(
						$property, 
						new PropertyNoteTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function createNote($propertyId)
	{
		$notes = $this->propertyNote->fill(Input::all());
		$notes->property_id = $propertyId;

		if($notes->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($notes->errors, 400);
	}

	public function updateNote($propertyId, $noteId)
	{
		$notes = $this->propertyNote->find($noteId);
		$notes->fill(Input::all());

		if($notes->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($notes->errors, 400);
	}

	public function destroyNote($id)
	{
		$notes = $this->propertyNote->find($id);

		if($notes == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Note.'
				], 404); // 403
		}

		$notes->delete();

		return Response::json(NULL, 200);
	}


	public function offers($propertyId)
	{
		$offers = $this->propertyOffer
					->where('property_id', '=', $propertyId)
					->get();	

		$data = $this->transformerManager
				->transform(
						$offers, 
						new PropertyOfferTransformer, 
						'collection'
						);

		return Response::json($data, 200);		
	}

	public function createOffer($propertyId)
	{
		$offers = $this->propertyNote->fill(Input::all());
		$offers->property_id = $propertyId;

		if($offers->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($offers->errors, 400);
	}

	public function acceptOffer($id, $offerId)
	{
		$offer = $this->propertyOffer->find($offerId);

		if($offer == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Offer.'
				], 404); // 403
		}

		$offer->status = 'Accept';
		$offer->save();

		return Response::json(NULL, 200);
	}

	public function declineOffer($id, $offerId)
	{
		$offer = $this->propertyOffer->find($offerId);

		if($offer == NULL)
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Offer.'
				], 404); // 403
		}

		$offer->status = 'Decline';
		$offer->save();

		return Response::json(NULL, 200);
	}

	public function docs()
	{
		return view('docs');
	}

}
