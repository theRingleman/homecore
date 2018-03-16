<?php

class UsersController extends Controller
{
	public function login()
    {

	}

	public function index()
    {
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
//	    @TODO One thing, I am going to have to add a check for unique emails, so we arent creating a
//        user multiple times.
		$user = new User($this->db);
		$valid = $user->validate($this->attributes);
		if ($valid === true) {
		    $password = password_hash($this->attributes->password, PASSWORD_DEFAULT);
		    $this->attributes->password = $password;
			$user->create($this->attributes);
			$endpoint = [
			    "message" => "User created successfully",
                "user" => $user->toEndPoint()
            ];
			$this->renderJson($endpoint);
		} else {
			$this->throwError($valid);
		}
		
	}

	public function authCheck()
    {

    }
}