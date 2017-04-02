<?php
trait SqlTableBucket {
	public static function each($fn) {
		$sql = 'SELECT * FROM '.static::TABLE;
		$stmt = App::$db->query($sql);
   		while($row = $stmt->fetch(PDO::FETCH_ASSOC) && $fn($row)) {
   			;
		}
	}
	public static function all() {
		return static::filter();
	}
	public static function filter($params = array()) {
		$sql = 'SELECT * FROM '.static::TABLE;
		$stmt = App::$db->query($sql);
   		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function get($pk) {
		$stmt = App::$db->prepare('SELECT '.implode(',',static::getFieldsNames()).' FROM '.static::TABLE.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		if ($stmt->execute()) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}
	protected static function set($pk,$data) {
		$sql;
		if ($pk) {
			$sql = 'UPDATE '.static::TABLE.' SET ';
			foreach (static::getFieldsNames() as $field_name) {
				$sql .= "$field_name=:$field_name,";
			}
			$sql = mb_substr($sql, 0, -1);
			$sql .= ' WHERE '.static::pk_name().' = :'.static::pk_name();
		} else {
			$sql = 'INSERT INTO '.static::TABLE.' (';
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
		$stmt = App::$db->prepare($sql);
		if ($pk) {
			$stmt->bindParam(':'.static::pk_name(), $pk);
		}
		foreach (static::getFieldsNames() as $field_name) {
			if (is_string($data[$field_name])) {
				$stmt->bindParam(':'.$field_name, $data[$field_name],PDO::PARAM_STR);
			} else {
				$stmt->bindParam(':'.$field_name, $data[$field_name]);
			}
		}
		if ($pk) {
			return $stmt->execute();
		} else {
			return App::$db->lastInsertId();	
		} 
	}
	public static function destroy($pk) {
		$stmt = App::$db->prepare('DELETE  FROM '.static::TABLE.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		App::debug($stmt);
		return $stmt->execute();
	}
}