<?php namespace App\Transformers;
use PropertyCompensation;
use League\Fractal\TransformerAbstract;

class PropertyCompensationTransformer extends TransformerAbstract
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
    public function transform(PropertyCompensation $compensation)
    {
        return [
            'id'    		            => (int) $compensation->id,
            'property_id'               => (int) $compensation->property_id,
            'compensation_of'           => '',
            'percentage'                => $compensation->percentage,
            'compensation'              => $compensation->compensation,
            'notes'    	                => $compensation->notes,
        ];
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeTest(Property $property)
    {
        $test = $property->property;

        return $this->item($test, new PropertyTransformer);
    }

}