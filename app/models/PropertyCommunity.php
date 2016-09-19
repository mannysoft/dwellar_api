<?php

class PropertyCommunity extends BaseModel {

	protected $table = 'properties_communities';

	protected $fillable = [
                    'property_id',
                    'community_name',
                    'association',
                    'association_fee',
                    'fees_included',
    ];

	protected static $insertRules = [
    				

    ];

    protected static $updateRules = [
    				

    ];

    protected static $customRule1 = [
    				'name' => 'required',
    ];

    protected static $messages = [];

    public $notFoundMessage = 'No such Property.';

    public function property()
    {
    	return $this->belongsTo('Property');
    }

    public function test()
    {
        //return $this->hasMany('PropertyImage', 'property_id');
    }
}
