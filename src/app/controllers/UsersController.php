<?php

class UsersController extends Controller
{
	public function signup()
	{
		try {
		    $userId = $this->auth->register($this->attributes->email, $this->attributes->password, $this->attributes->username);

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

	public function login() {
	    if ($this->attributes->remember == 1) {
            $rememberDuration = (int) (60 * 60 * 24 * 365.25);
        } else {
	        $rememberDuration = null;
        }
        try {
            $this->auth->login($this->attributes['email'], $this->attributes['password'], $rememberDuration);

            // user is logged in
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            // wrong email address
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            // wrong password
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // email not verified
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