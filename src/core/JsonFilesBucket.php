<?php
trait JsonFilesBucket {
	public static function get($pk) {//TODO protected
		$stmt = App::$db->prepare('SELECT * FROM '.static::NAME.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		if ($stmt->execute()) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}
	public function load() {
		if ($model = static::get($this->pk)) {
			//unset($model[$this->pk_name()]);
			foreach (static::getFieldsNames() as $key) {
				$this->$key = $model[$key];
			}
			//TODO $this->pk = $model[$this->pk_name];
			//unset($model[$this->pk_name]);
			return $model;		
		}
	}
	protected static function set($data) {
		$sql;
		if (in_array(static::pk_name(), $data)) {
			$sql = 'UPDATE '.static::NAME.' SET ';
			foreach (static::getFieldsNames() as $field_name) {
				$sql .= "$field_name=:$field_name,";
			}
			$sql = mb_substr($sql, 0, -1);
			$sql .= ' WHERE '.static::pk_name().' = :'.static::pk_name();
		} else {
			$sql = 'INSERT INTO '.static::NAME.' (';
			$fields_sql = '';
			$values_sql = '';
			foreach (static::getFieldsNames() as $field_name) {
				$fields_sql .= $field_name.','; 
				$values_sql .= ':'.$field_name.',';
			}
			$fields_sql = mb_substr($fields_sql, 0, -1);
			$values_sql = mb_substr($values_sql, 0, -1);
			$sql .= $fields_sql.') VALUES ('.$values_sql.');';
		}
		echo $sql;
		$stmt = App::$db->prepare($sql);
		if (in_array(static::pk_name(), $data)) {
			$stmt->bindParam(':'.static::pk_name(), $data[static::pk_name()]);
		}
		foreach (static::getFieldsNames() as $field_name) {
			if (is_string($data[$field_name])) {
				$stmt->bindParam(':'.$field_name, $data[$field_name],PDO::PARAM_STR);
			} else {
				$stmt->bindParam(':'.$field_name, $data[$field_name]);
			}
		}
		return $stmt->execute();
	}
	public function save() {
		if ($this->validate()){
			return static::set($this->fields);	
		}
	}
	public static function destroy($pk) {
		$stmt = App::$db->prepare('DELETE  FROM '.static::NAME.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		App::debug($stmt);
		return $stmt->execute();
	}
}