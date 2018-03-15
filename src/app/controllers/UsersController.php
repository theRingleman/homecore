<?php

class UsersController extends Controller
{
	public function signup()
	{
		$auth = $this->f3->get("AUTH");
		try {
		    $userId = $auth->register($this->attributes->email, $this->attributes->password, $this->attributes->username, function ($selector, $token) {
		        print_r('We just signed you up');
		    });

		    print_r("we have signed up a new user with the ID {$userId}");
		}
		catch (\Delight\Auth\InvalidEmailException $e) {
		    // invalid email address
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
		    // invalid password
		}
		catch (\Delight\Auth\UserAlreadyExistsException $e) {
		    print_r('Sorry but you are already here...');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
		    // too many requests
		}
	}
	public function index(){

		$users = (new User($this->db))
			->all();

		$endpoint = [];

		foreach ($users as $user) {
			$endpoint[] = $user->toEndPoint();
		}

		$this->renderJson($endpoint);
	}

	public function show()
	{
		$user = new User($this->db);
		$user->findById($this->params['id']);
		if (!is_null($user->id)) {
			$this->renderJson($user->toEndPoint());
		} else {
			
		}
	}

	public function create()
	{
		$user = new User($this->db);
		$valid = $user->validate($this->attributes);
		if ($valid === true) {
			$user->create($this->attributes);
			$this->renderJson($user->toEndPoint());
		} else {
			// TODO we need to figure out how to output the validation errors on error.
			$this->throwError($valid);
		}
		
	}
}