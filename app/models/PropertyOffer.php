<?php

class PropertyOffer extends BaseModel {

	protected $table = 'properties_offers';

	protected $fillable = [
                    'property_id',
                    'user_id',
                    'name',
                    'purchase_price',
                    'total_deposit',
                    'down_payment',
                    'status',
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
