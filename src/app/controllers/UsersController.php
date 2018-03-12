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
		$params = $this->f3->get('PARAMS');
		$user = new User($this->db);
		$user->getById($params['id']);
		if (!is_null($user->id)) {
			$this->renderJson($user->toEndPoint());
		} else {
			$this->f3->error(404, "Sorry the user requested does not exist.");
		}
	}
}