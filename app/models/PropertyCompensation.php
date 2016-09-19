<?php

class PropertyCompensation extends BaseModel {

	protected $table = 'properties_compensations';

	protected $fillable = [
    				'compensation_of',
                    'property_id',
                    'percentage',
                    'compensation',
                    'notes',
    				

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
