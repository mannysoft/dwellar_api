<?php namespace App\Transformers;
use PropertyOffer;
use League\Fractal\TransformerAbstract;

class PropertyOfferTransformer extends TransformerAbstract
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
    public function transform(PropertyOffer $notes)
    {
        return [
            'id'    		    => (int) $notes->id,
            'property_id'       => (int) $notes->property_id,
            'purchase_price'    => $notes->purchase_price,
            'total_deposit'     => $notes->total_deposit,
            'down_payment'      => $notes->down_payment,
            'status'            => $notes->status,

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