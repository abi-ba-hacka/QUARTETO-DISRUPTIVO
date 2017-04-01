<?php
class Model {
	public static $VALIDATORS = array();
	protected $pk;
	protected $fields = array();
	public static function each($fn) {
		$sql = 'SELECT * FROM '.static::TABLE;
		$stmt = App::$db->query($sql);
   		while($row = $stmt->fetch(PDO::FETCH_ASSOC) && $fn($row)) {
   			;
		}
	}
	public static function all() {
		$sql = 'SELECT * FROM '.static::TABLE;
		$stmt = App::$db->query($sql);
   		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function __construct($pk = null) {
		if($pk != null) {
			$this->pk = $pk;
			$this->load();
		}
	}
	public static function get($pk) {//TODO protected
		$stmt = App::$db->prepare('SELECT * FROM '.static::TABLE.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		if ($stmt->execute()) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}
	public function load() {
		if ($model = static::get($this->pk)) {
			foreach ($model as $key => $value) {
				$this->key = $value;
			}
			//TODO $this->pk = $model[$this->pk_name];
			//unset($model[$this->pk_name]);
			return $model;		
		}
	}
	protected static function set($pk, $data) {
		if ($pk) {
			$sql = 'UPDATE '.static::TABLE.' SET (';
			foreach ($data as $key => $value) {
				$sql .= "$key = $value, ";
			}
			$sql = mb_substr($sql, 0, -1);
			$sql .= ') WHERE '.static::pk_name().'=:'.static::pk_name();
		} else {
			$sql = 'INSERT INTO '.static::TABLE.' (';
			$fields = '';
			$values = '';
			foreach ($data as $key => $value) {
				$fields .= $key.','; 
				$values .= ':'.$key.',';
			}
			$fields = mb_substr($fields, 0, -1);
			$values = mb_substr($values, 0, -1);
			$sql .= $fields.') VALUES ('.$values.');';
		}
		$stmt = App::$db->prepare($sql);
		if ($pk) $stmt->bindParam(':'.static::pk_name(), $pk);
		foreach ($data as $key => $value) {
			$stmt->bindParam(':'.$key, $data[$key]);
		}
		$stmt->execute();
	}
	public function save() {
		$this->validate();
		return static::set($this->pk, $this->fields);
	}

	public static function destroy($pk) {
		$stmt = App::$db->prepare('DELETE  FROM '.static::TABLE.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		App::debug($stmt);
		return $stmt->execute();
	}
	public function drop() {
		return static::destroy($this->pk);
	}
	public static function pk_name() {
		$keys = array_keys(static::PK);
		return $keys[0];
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
			$_SESSION['errors'] = $validator->errors();
		    throw new InputException("Error Processing Request", 1);
		}
	}

}