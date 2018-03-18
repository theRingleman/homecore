<?php

class Controller {

	/**
	 * @param $db DB FatFree database object for easy access.
	 */
	public $db;

	/**
	 * @param $f3 Base FatFree instance for easy access.
	 */
	public $f3;

	/**
	 * @param $params array route parameters.
	 */
	public $params;
	/**
	 * @param $attributes stdClass JSON attributes passed to route.
	 */
	public $attributes;
	/**
	 * @param $errors array Errors passed when needed for listing errors.
	 */
	public $errors;

	/**
	 * @param $auth HomeAuth For all things Authentication.
	 */
	protected $auth;

	/**
	 * @param $allowedRoutes array Add any routes in this array that you would like to allow without
     * authenticating.
	 */
	protected $allowedRoutes = [
	    "/users/login"
    ];

	public function __construct($f3)
	{
		$this->f3 = $f3;
		$this->db = $f3->get("DB");
		$this->auth = $f3->get('AUTH');
	}

	public function beforeroute() 
	{
        $this->params = $this->f3->get('PARAMS');
        if (!$this->checkRoute()) {
            $this->validate();
        }
        if ($this->f3->exists('BODY')) {
            $this->attributes = json_decode($this->f3->get('BODY'));
        }
	}

	public function afterroute() 
	{
		
	}

	public function throwError($errors)
	{
		$this->f3->set("MODELERRORS", $errors);
		$this->f3->error(404, 'Sorry but some information could not be validated');
	}

	public function renderError()
	{
		$message = [
			"message" => $this->f3->get('ERROR.text'),
			"errors" => $this->f3->get("MODELERRORS")
		];
		$this->f3->set('response', $message);
		echo \Template::instance()->render('json.php');
	}

	public function renderJson($response)
	{
		$this->f3->set('response', $response);
		echo \Template::instance()->render('json.php');
	}

	private function getToken()
    {
        if (is_null($header = $this->f3->get("HEADERS")['Authorization'])) {
            return null;
        } else {
            preg_match('/Bearer\s(\S+)/', $header, $matches);
            return $matches[1];
        }

    }

    private function checkRoute()
    {
        return in_array($this->params[0], $this->allowedRoutes);
    }

    private function validate()
    {
        if (is_null($token = $this->getToken())) {
            $this->f3->error(401, "Missing the proper request headers: Access Denied.");
        } else {
            if ((new HomeAuth)->validateToken($token)) {
                print_r("Your token has been validated, welcome.");
                exit;
            } else {
                print_r("Nope get the f out...");
                exit;
            }
        }
    }
}