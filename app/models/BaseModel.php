<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent{
	
	public $errors;
	public $validation;
	public $rawErrors;
	public $customRule;
	public $customMessage;
	
	//----------------------------------------------------------------	

	public static function boot()
	{
		parent::boot();
		
		static::saving(function($model)
		{
			if ( ! $model->force) return $model->validate();
		});
	}

	//----------------------------------------------------------------	
	
	public function validate()
	{
		$rules = self::processRules(static::$insertRules);
		
		// Lets see if the key has value
		if($this->getKey() != NULL)
		{
			// Lets use the update rules
			$rules = self::processRules(static::$updateRules);
		}
		
		// Lets check if we have custom rules
		if(isset($this->customRule))
		{
			$rule = 'customRule'.$this->customRule;

			$rules = self::processRules(static::$$rule);
		}
		
		$messages = static::$messages;

		// Well lets check if we have custom messages also
		if(isset($this->customMessage))
		{
			$message = 'customMessage'.$this->customMessage;

			$messages = static::$$message;
		}
		
		$validation = Validator::make($this->attributes, $rules, $messages);
		
		if($validation->passes()) return true;
		
		$this->errors 		= $validation->messages()->all();
		$this->validation 	= $validation;
		
		return false;
	}
	
	/**
	 * Process validation rules.
	 *
	 * @param  array  $rules
	 * @return array  $rules
	 */
	protected function processRules($rules)
	{
		$id = $this->getKey();
		
		array_walk($rules, function(&$item) use ($id)
		{
			// Replace placeholders
			$item = stripos($item, ':id:') !== false ? str_ireplace(':id:', $id, $item) : $item;
		});
		
		return $rules;
	}

	//----------------------------------------------------------------

	public function rawErrors()
	{
		$rawErrors = '';

		foreach($this->errors as $error)
		{
			$rawErrors.= $error.' ';
		}

		return $rawErrors;	
	}

	//----------------------------------------------------------------

	public function rawErrorsBr()
	{
		$rawErrors = '';

		foreach($this->errors as $error)
		{
			$rawErrors.= $error.' <br>';
		}

		return $rawErrors;
	}

	public function rawErrorsDivs()
	{
		$rawErrors = '';

		foreach ($this->validation->messages()->all('<div class="error-msg"><i class="fa fa-chevron-right"></i>:message</div>') as $message)
		{
		    $rawErrors.= $message;
		}

		return $rawErrors;
	}		

}