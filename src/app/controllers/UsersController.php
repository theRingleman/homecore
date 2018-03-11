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
		$user->load(['id=?', '1']);
		
		$this->renderJson($user->toEndPoint());
	}
}