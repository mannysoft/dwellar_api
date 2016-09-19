<?php namespace App\Transformers;
use Property;
use League\Fractal\TransformerAbstract;

class PropertyTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'test'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Property $property)
    {
        $images = $property->images;

        $exterior = [
                    'road_front_age' => 'Yes',
                    'driveway' => 'No',
                    'pool' => 'Yes',
                    ];

        $legislative_charges        = $property->charges;
        
        $offers                     = $property->offers;
        $notes                      = $property->notes;
        $compensations              = $property->compensations;
        $communities                = $property->communities;

        return [
            'id'    				=> (int) $property->id,
            'user_id'               => $property->user_id,
            'project_name'          => $property->project_name,
            //'listing_delete'        => $property->listing_delete,
            //'address_type_code'    	=> $property->address_type_code,
            'title'     => $property->title,
            'address'      => $property->address,
            'bedrooms'     => $property->bedrooms,
            'bathrooms'     => $property->bathrooms,
            'builtup_area'     => $property->builtup_area,
            'carpet_area'     => $property->carpet_area,
            'agreement_value'     => $property->agreement_value,
            'total_cost'     => $property->total_cost,
            'availability'     => $property->availability_type_code,
            'views'     => $property->views,
            'furnishings'     => $property->furnishings,
            'source_code'     => $property->source_code,
            'description'     => $property->description,

            //'monthly_rent'          => $property->monthly_rent,
            //'sqft'                  => $property->sqft,
            
            //'category_code'     => $property->category_code,
            //'availability_type_code'     => $property->availability_type_code,
            
            
            //'building'     => $property->building,
            //'possession_date'     => $property->possession_date,
            
            
            'area'     => $property->area,
            //'city'     => $property->City,
            //'zip'     => $property->Zip,
            
            'price_sqft'     => $property->price_sqft,
            
            'parking_spaces'     => $property->parking_spaces,
            

            'images'    => $images,

            //'lot_size' => $property->lot_zize,

            

            'views' => 'East',

            'legislative_charges' => $legislative_charges,
            'offers' => $offers,
            'notes' => $notes,
            'compensations' => $compensations,
            'communities' => $communities,
            'exterior' => $exterior,
            

            //'created_at'    		=> $property->created_at,
        ];
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeTest(Property $property)
    {
        //$test = $property->property;

        //return $this->item($test, new PropertyTransformer);
    }

}