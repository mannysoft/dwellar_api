<?php namespace App\Transformers;
use Contact, Request;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
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
    public function transform(Contact $contact)
    {
        return [
            'id'    				=> (int) $contact->id,
            'user_id'               => (int) $contact->user_id,
            //'photo'                 => ($contact->photo == '') ? null : Request::root().'/contacts/'.$contact->user_id.'/'.$contact->photo,
            'photo'                 => ($contact->photo == '') ? null : Request::root().'/contacts/'.$contact->id.'/photo',
            'name'    	            => $contact->name,
            'birth_date'            => $contact->birth_date,
            'interested_in'         => '',
            'offers_made'           => 0,
            'address'               => $contact->address,
            'city'                  => $contact->city,
            'state'                 => $contact->state,
            'country'               => $contact->country,
            'zip'                   => $contact->zip,
            'mobile'                => $contact->mobile,
            'email'                 => $contact->email,
            'tags'                  => $contact->tags,
            'description'           => $contact->description,
            'fb'                    => $contact->fb,
            'twitter'               => $contact->twitter,
            'google'                => $contact->google,

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