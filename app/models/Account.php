<?php

class Account extends BaseModel {
	
	protected $table = 'users';

	protected $fillable = [
    				'email',
    				'password',
                    'otp',
    				'first_name',
    				'last_name',
    				'organization_name',
    				'organization_id',
    				'organization_address',
    				'account_timezone',
    				'account_locale',
    				'state',
    				'reason_code',
    				'reason_notes'

    ];

	protected static $insertRules = [
    				'email' 				=> 'required|email|unique:users,email|max:200',
    				'password' 				=> 'required',
    				'first_name' 			=> 'required|max:200',
    				'last_name' 			=> 'required|max:200',
    				'account_timezone' 		=> 'required',
    				'account_locale' 		=> 'required',

    ];

    protected static $updateRules = [
    				'email' 				=> 'required|email|unique:users,email,:id:|max:200',
    				'first_name' 			=> 'required|max:200',
    				'last_name' 			=> 'required|max:200',
    				'account_timezone' 		=> 'required',
    				'account_locale' 		=> 'required',

    ];

    protected static $customRule1 = [
    				'name' => 'required',
    ];

    protected static $messages = [];

    public function organizations()
    {
    	return $this->belongsTo('Organization', 'organization_id');
    }

    public function scopeByStatus($query, $status)
    {
    	if($status and $status != '')
    	{
    		return $query->where('state', $status);
    	}
    }
}
