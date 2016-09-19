<?php namespace App\Transformers;
use PropertyCommunity;
use League\Fractal\TransformerAbstract;

class PropertyCommunityTransformer extends TransformerAbstract
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
    public function transform(PropertyCommunity $communities)
    {
        return [
            'id'    		             => (int) $communities->id,
            'property_id'                => (int) $communities->property_id,
            'community_name'             => $communities->community_name,
            'association'                => $communities->association,
            'association_fee'            => $communities->association_fee,
            'fees_included'              => $communities->fees_included,

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