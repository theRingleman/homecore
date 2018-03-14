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

	public function edit($id, $values)
	{
		$this->load(['id=?', $id]);
		$this->copyFrom($values);
		$this->update();
	}

	public function create($values)
	{
		$this->copyFrom($values);
		$this->save();	
	}

	public function validate($values)
	{
		$values = (array)$values;
		$validator = new GUMP;
		$values = $validator->sanitize($values);
		$validator->validation_rules($this->validationRules);
		$validator->filter_rules($this->filterRules);

		$validatedData = $validator->run($values);

		return $vaildatedData ? $validatedData : $validator->get_errors_array();
	}

	public function delete($id)
	{
		$this->load(['id' => $id]);
		$this->erase();
	}
}