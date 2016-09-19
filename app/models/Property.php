<?php

class Property extends BaseModel {
	
    //use ModelTrait;

	protected $table = 'properties';

	protected $fillable = [
    				'project_name',
    				

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

    public function users()
    {
    	return $this->belongsTo('User');
    }

    public function images()
    {
        return $this->hasMany('PropertyImage', 'property_id');
    }

    public function charges()
    {
        return $this->hasMany('PropertyLegislativeCharge', 'property_id');
    }

    public function communities()
    {
        return $this->hasMany('PropertyCommunity', 'property_id');
    }

    public function offers()
    {
        return $this->hasMany('PropertyOffer', 'property_id');
    }

    public function notes()
    {
        return $this->hasMany('PropertyNote', 'property_id');
    }

    public function compensations()
    {
        return $this->hasMany('PropertyCompensation', 'property_id');
    }
}
