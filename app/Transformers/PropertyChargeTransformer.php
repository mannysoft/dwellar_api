<?php namespace App\Transformers;
use PropertyLegislativeCharge;
use League\Fractal\TransformerAbstract;

class PropertyChargeTransformer extends TransformerAbstract
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
    public function transform(PropertyLegislativeCharge $charges)
    {
        return [
            'id'    		            => (int) $charges->id,
            'property_id'               => (int) $charges->property_id,
            'description'               => $charges->description,
            'percentage'                => $charges->percentage,
            'amount'                    => $charges->amount,
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