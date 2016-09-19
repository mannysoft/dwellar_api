<?php

class PropertyImage extends BaseModel {
	
	protected $table = 'properties_images';

	protected $fillable = [
    				'property_id',
                    'filename',
                    'filename',
                    'mime',
                    'primary_image',
    				

    ];

    protected $appends = ['url'];

	protected static $insertRules = [
    				

    ];

    protected static $updateRules = [
    				

    ];

    protected static $customRule1 = [
    				'name' => 'required',
    ];

    protected static $messages = [];

    public function getUrlAttribute()
    {
        return Request::root().'/properties/'.$this->property_id.'/photo/'.$this->id;
    }
}
