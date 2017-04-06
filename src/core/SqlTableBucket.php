<?php
class Result implements Iterator {
	public mixed current ( void )
	public scalar key ( void )
	public void next ( void )
	public void rewind ( void )
	public boolean valid ( void )
}
trait SqlTableBucket {
	public static function each($fn) {
		$sql = 'SELECT * FROM '.static::NAME;
		$stmt = App::$db->query($sql);
   		while($row = $stmt->fetch(PDO::FETCH_ASSOC) && $fn($row)) {
   			;
		}
	}
	public static function rows($selection=null) {
		$field_list = '*';
		if ($selection == null) {
			$selection = array('fields'=>array_keys(static::FIELDS));
		}
		if (!in_array(static::pk_name(), $selection['fields'])) {
			$selection['fields'][] = static::pk_name();
		}
		if ($selection && isset($selection['fields'])) {
			$field_list = implode(', ', $selection['fields']);
		}
		
		foreach ($selection as $key => $value) {
			// $field_list = $value.',';
		}
		$field_list = implode(',', $selection['fields']);
		$sql = 'SELECT '.$field_list.' FROM '.static::NAME;
		$stmt = App::$db->query($sql);
   		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function get($pk) {
		$stmt = App::$db->prepare('SELECT '.implode(',',static::getFieldsNames()).' FROM '.static::NAME.' WHERE '.static::pk_name().' = :'.static::pk_name());
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
		return $stmt->execute();
	}
	public static function destroy($pk) {
		$stmt = App::$db->prepare('DELETE  FROM '.static::NAME.' WHERE '.static::pk_name().' = :'.static::pk_name());
		$stmt->bindParam(':'.static::pk_name(), $pk);
		App::debug($stmt);
		return $stmt->execute();
	}
}