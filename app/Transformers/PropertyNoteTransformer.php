<?php namespace App\Transformers;
use PropertyNote;
use League\Fractal\TransformerAbstract;

class PropertyNoteTransformer extends TransformerAbstract
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
    public function transform(PropertyNote $notes)
    {
        return [
            'id'    		    => (int) $notes->id,
            'property_id'       => (int) $notes->property_id,
            'title'             => $notes->title,
            'content'           => $notes->content,

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