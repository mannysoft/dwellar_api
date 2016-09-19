<?php

class Organization extends BaseModel {

    protected $table = 'organizations';

    protected $fillable = [
    						
    				'name',

    ];
    protected static $insertRules = [
    				'name' => 'required'
    ];

    protected static $customRule1 = [
    				'name' => 'required',
    ];

    protected static $messages = [];
}