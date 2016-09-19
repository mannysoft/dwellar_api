<?php

class Contact extends BaseModel {
	
	protected $table = 'contacts';

	protected $fillable = [
    				'user_id',
    				'photo',
                    'mime',
                    'name',
                    'birth_date',
    				'address',
    				'city',
    				'state',
    				'country',
    				'zip',
    				'mobile',
    				'email',
    				'tags',
    				'description',
    				'fb',
                    'twitter',
                    'google',
                    'revision_number'

    ];

	protected static $insertRules = [
                    'name'              => 'required',
                    'revision_number'   => 'required',

    ];

    protected static $updateRules = [
    				//'email' 				=> 'required|email|unique:users,email,:id:|max:200',

    ];

    protected static $messages = [];

    public function organizations()
    {
    	return $this->belongsTo('Account', 'user_id');
    }
}
