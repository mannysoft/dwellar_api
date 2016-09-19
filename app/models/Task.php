<?php

class Task extends BaseModel {
	
	protected $table = 'tasks';

	protected $fillable = [
    				'user_id',
    				'task_name',
                    'assigned_contact_id',
                    'due_date',
    				'priority',
    				'categories',
    				'description',
    				'status',
                    'revision_number'

    ];

	protected static $insertRules = [
                    'task_name'         => 'required',
                    'revision_number'   => 'required',

    ];

    protected static $updateRules = [
    				//'email' 				=> 'required|email|unique:users,email,:id:|max:200',

    ];

    protected static $messages = [];

    public function setUserfdfdfdIdAttribute()
    {
        //$this->attribute['user_id'] = Auth::user()->id;
    }

    public function scopeOrdering($query)
    {
        $orderTime = Input::get('order_time');
        $priority = Input::get('priority');

        //if(in_array($orderTime, ['recent','today','7days']))

        //if(in_array($priority, ['high','medium','low']))
        //return $query->orderBy();
    }    

    public function user()
    {
    	return $this->belongsTo('User', 'user_id');
    }

    public function contact()
    {
        return $this->belongsTo('Contact', 'assigned_contact_id');
    }
}
