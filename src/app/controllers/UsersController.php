<?php

class UsersController extends Controller
{
    /**
     * Allows us to log our user in, this will eventually produce a JWT.
     */
    public function login()
    {
        $user = new User($this->db);
        $user->findByEmail($this->attributes->email);
        if (password_verify($this->attributes->password, $user->password)) {
            $this->renderJson(["message" => "You are now logged in"]);
        } else {
            $this->renderJson(["message" => "Something went horribly wrong..."]);
        }
	}

    /**
     * This shows a list of users, this will be moved to an admin section.
     */
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

    /**
     * Shows a specific user.
     */
    public function show()
	{
		$user = new User($this->db);
		$user->findById($this->params['id']);
		if (!is_null($user->id)) {
			$this->renderJson($user->toEndPoint());
		} else {
			
		}
	}

    /**
     * Allows us to create a new user.
     * @throws Exception
     */
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