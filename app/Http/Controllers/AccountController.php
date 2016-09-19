<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Account, Organization;
use Input, Response, Hash;
class AccountController extends Controller {
	
	protected $account;
	protected $organization;
	
	public function __construct(Account $account, Organization $organization)
	{
		$this->middleware('auth.basic');
		$this->account 		= $account;
		$this->organization = $organization;
	}

	public function create()
	{
		// We have to check the organization name if exists
		$organization = null;
		if(Input::get('organization_name'))
		{
			$organization = $this->organization
				->where('name', '=', Input::get('organization_name'))
				->first();
		}

		if($organization != null)
		{
			return Response::json(['messages' => 'Organization already exists!'], 400);
		}

		$organizationId = 0;
		// If organization_id is blank then lets create the
		// organization
		if(Input::get('organization_id') == '')
		{
			$organization 			= $this->organization->fill([]);
			$organization->name 	= Input::get('organization_name');
			$organization->address 	= Input::get('organization_address');
			$organization->save();

			$organizationId = $organization->id;
		}
		else
		{
			$organizationId = Input::get('organization_id');
		}

		$account = $this->account->fill(Input::except('password','organization_name', 'organization_address'));
		$account->organization_id = $organizationId;
		
		if(Input::get('password'))
		{
			$account->password = Hash::make(Input::get('password'));
		}
		
		if($account->save())
		{
			return Response::json(NULL, 200);
		}

		return Response::json($account->errors, 400);
	}

	public function show($id)
	{
		$accountArray = [];

		$account = $this->account->find($id);

		if($account)
		{
			$accountArray = [
				'id' 					=> $account->id,
				'account_state' 		=> $account->state,
				'email' 				=> $account->email,
				'first_name' 			=> $account->first_name,
				'last_name' 			=> $account->last_name,
				'organization_id' 		=> $account->organization_id,
				'organization_name' 	=> $account->organizations->name,
				'organization_address' 	=> $account->organizations->address,
				'account_timezone' 		=> $account->account_timezone,
				'account_locale' 		=> $account->account_locale,
			];

			return Response::json($accountArray, 200);

		}

		return $this->noUserAccount();
	}

	public function update($id)
	{
		$account = $this->account->find($id);

		if($account)
		{
			$account->email					= Input::get('email');
			$account->first_name 			= Input::get('first_name');
			$account->last_name 			= Input::get('last_name');
			
			$organization 					= $this->organization
											->find($account->organization_id);
			$organization->name 			= Input::get('organization_name');
			$organization->address 			= Input::get('organization_address');
			$organization->save();

			if($account->save())
			{
				$accountObject = [
					'email' 				=> $account->email,
					'first_name' 			=> $account->first_name,
					'last_name' 			=> $account->last_name,
					'organization_name' 	=> $account->organizations->name,
					'organization_address' 	=> $account->organizations->address,
				];
				
				return Response::json($accountObject, 200);
			}

			return Response::json($account->errors, 400);
		}

		return $this->noUserAccount();
	}

	public function closeAccount($id)
	{
		return $this->changeAccountState($id, 'closed');
	}

	public function reopenAccount($id)
	{
		return $this->changeAccountState($id, 'active');
	}

	public function suspendAccount($id)
	{
		return $this->changeAccountState($id, 'inactive');
	}

	public function index()
	{
		$accounts = $this->account
				->byStatus(Input::get('status'))
				->paginate(50);

		$accountArray = [];

		foreach($accounts as $account)
		{
			$accountArray[] = [
				'id' 					=> $account->id,
				'account_state' 		=> $account->state,
				'email' 				=> $account->email,
				'first_name' 			=> $account->first_name,
				'last_name' 			=> $account->last_name,
				'organization_id' 		=> $account->organization_id,
				'organization_name' 	=> $account->organizations->name,
				'organization_address' 	=> $account->organizations->address,
			];
		}

		return Response::json($accountArray, 200);
	}

	public function passwordAccount($id)
	{
		$account = $this->account->find($id);

		if($account)
		{
			if (! (Hash::check(Input::get('current_password'), $account->password)) )
			{ 
			    return Response::json([
					'error_code' => 2, 
					'error_description' => 'You have enter an invalid current password'
					], 403);
			}

			$account->password 	= Hash::make(Input::get('new_password'));
			$account->otp 		= Input::get('otp');
			$account->save();

			return Response::json(NULL, 200);
		}
		
		return $this->noUserAccount();
	}

	public function changeAccountState($id, $state)
	{
		$account = $this->account->find($id);

		if($account)
		{
			$account->state			= $state;
			$account->reason_code	= Input::get('close_reason_code');
			$account->reason_notes	= Input::get('close_reason_notes');
			if($account->save())
			{
				return Response::json(NULL, 200);
			}

			return Response::json($account->errors, 400);
		}

		return $this->noUserAccount();
	}

	public function noUserAccount()
	{
		return Response::json([
				'error_code' => 1, 
				'error_description' => 'No such Account.'
				], 404); // 403
	}

}
