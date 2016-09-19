<?php namespace App\Transformers;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

use Response;

class TransformerManager
{
	public function transform($data, $transformer, $collectionItem = 'item')
	{
		if($data == null and $collectionItem == 'item')
		{
			return Response::json([
				'error_code' => 1, 
				'error_description' => 'Record not found.'
				], 404); // 403
		}
		
		$manager = new Manager();
		$manager->setSerializer(new DataArraySerializer());

		if($collectionItem == 'item')
		{
			$resource = new Fractal\Resource\Item($data, $transformer);
		}
		else
		{
			$resource = new Fractal\Resource\Collection($data, $transformer);
		}
		
		$data = $manager->createData($resource)->toArray();

		// We want to return data key
		return $data['data'];
	}
}

