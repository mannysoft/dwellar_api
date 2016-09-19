<?php

class PropertyLegislativeCharge extends BaseModel {

	protected $table = 'properties_legislative_charges';

	protected $fillable = [
                    'property_id',
                    'description',
                    'percentage',
                    'amount',
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
