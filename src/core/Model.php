<?php
class Model {
	public static $VALIDATORS = array();
	protected $pk;
	protected $fields = array();
	public function __construct($pk = null) {
		if($pk != null) {
			$this->pk = $pk;
			$this->load();
		}
	}
	public function fromArray($array,$overwrite = true) {
		foreach (static::FIELDS as $key => $value) {
			if (isset($array[$key])) {
				$this->fields[$key] = $array[$key];
			}
		}
	}
	public function toArray() {
		return $this->fields;
	}
	public function load() {
		if ($model = static::get($this->pk)) {
			foreach (static::getFieldsNames() as $key) {
				$this->$key = $model[$key];
			}
			return $model;		
		}
	}
	public function save() {
		if ($this->validate()){   
			if (static::set($this->pk, $this->fields)) {
				$this->pk = App::$db->lastInsertId();
				return $this->pk;
			}
		}
	}
	public function drop() {
		return static::destroy($this->pk);
	}
	public static function pk_name() {
		return array_keys(static::PK)[0];
	}
	public static function getFieldsNames() {
		return array_keys(static::FIELDS);
	}
	public function __get($name) {
		if (isset($this->fields[$name])) {
			return isset($this->fields[$name]);
		}
	}
	public function __set($name, $value) {
		$this->fields[$name] = $value;
	}
	public function validate() {
		$validator = new Valitron\Validator($this->fields);
		$validator::addRule('YYYY-mm-dd', function($field, $value, array $params, array $fields) {
			$date = new DateTime($value);
			if ($date->format('Y-m-d') == $value) return true;
		}, 'La fecha debe tener el formato YYYY-mm-dd.');
		foreach (static::FIELDS as $field => $rules) {
			foreach ($rules as $rule) {
				if (is_array($rule)) {
					list($rule_name, $args) = $rule;
					$validator->rule($rule_name, $field, $args);
				} elseif (is_string($rule)) {
					$validator->rule($rule, $field);
				}
			}
		}
		if ($validator->validate()) {
			return true;
		} else {
			$_SESSION['errors']['form'] = $validator->errors();
			$_SESSION['referral_request'] = $_REQUEST;
			return false;
		}
	}
}