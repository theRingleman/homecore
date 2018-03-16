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
		$user = new User($this->db);
		$valid = $user->validate($this->attributes);
		if ($valid === true) {
			$user->create($this->attributes);
			$this->renderJson($user->toEndPoint());
		} else {
			$this->throwError($valid);
		}
		
	}

	public function authCheck()
    {

    }
}