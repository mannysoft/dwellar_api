<?php

class PropertyNote extends BaseModel {

	protected $table = 'properties_notes';

	protected $fillable = [
                    'property_id',
                    'title',
                    'content',
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
