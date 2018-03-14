<?php

class Model extends DB\SQL\Mapper
{
	public $attributes = [];
	public $validationRules;

	public function toEndPoint(){
		$attributes = [];
		foreach ($this->attributes as $attribute) {
			$attributes[$attribute] = $this->{$attribute};
		}
		return $attributes;
	}

	public function findById($id){
		$this->load(['id=?', $id]);
		return $this->query;
	}

	public function all() 
	{
		$this->load();
		return $this->query;
	}

	public function edit($id, $attributes)
	{
		$this->load(['id=?', $id]);
		$this->copyFrom($attributes);
		$this->update();
	}

	public function create($attributes)
	{
		$this->copyFrom($attributes);
		if ($this->validate($attributes)) {
			$this->save();	
		}
	}

	public function validate($attributes)
	{
		$attributes = (array)$attributes;
		$validator = new GUMP;
		$attributes = $validator->sanitize($attributes);
		$validator->validation_rules($this->validationRules);
		$validator->filter_rules($this->filterRules);

		$validatedData = $validator->run($attributes);
		if ($validatedData === true) {
			return true;
		} else {
			return $validator->get_errors_array();
		}
	}

	public function delete($id)
	{
		$this->load(['id' => $id]);
		$this->erase();
	}
}