<?php

class LogApi extends BaseModel {

    protected $table = 'logs';

    protected $fillable = [	
    				'user_id',
                    'api',
                    'method',

    ];
    protected static $insertRules = [];

    protected static $customRule1 = [];

    protected static $messages = [];
}