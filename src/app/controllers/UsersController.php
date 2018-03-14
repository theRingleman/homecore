<?php

class UsersController extends Controller
{
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
			$this->f3->error(404, "Sorry, but the info you passed in cannot be validated.");
		}
		
	}
}