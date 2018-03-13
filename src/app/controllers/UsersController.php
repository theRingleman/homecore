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
			$this->f3->error(404, "Sorry the user requested does not exist.");
		}
	}

	public function create()
	{
		$user = new User($this->db);
		$attributes = json_decode($this->f3->get('BODY'));
		$user->create($attributes);

		print_r($user);
	}
}