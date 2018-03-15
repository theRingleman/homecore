<?php

class Controller {

	public $db;

	public $f3;

	public $params;
	public $attributes;
	public $errors;
	protected $auth;

	public function __construct($f3)
	{
		$this->f3 = $f3;
		$this->db = $f3->get("DB");
		$this->auth = $f3->get('AUTH');
	}

	public function beforeroute() 
	{
		$this->params = $this->f3->get('PARAMS');
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
}