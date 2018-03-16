<?php

class Model extends DB\SQL\Mapper
{
	public $attributes = [];
	public $validationRules;
	public $filterRules;
	public $errors;

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

    /**
     * @param $values
     * @return array|bool|null
     * @throws Exception
     */
    public function validate($values)
	{
		$validator = new GUMP;
		$values = $validator->sanitize((array)$values);
		$validator->validation_rules($this->validationRules);
		$validator->filter_rules($this->filterRules);

		$validatedData = $validator->run($values);

        return $validatedData === false ? $validator->get_errors_array() : true;
	}

	public function delete($id)
	{
		$this->load(['id' => $id]);
		$this->erase();
	}
}