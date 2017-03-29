<?php

/**
 * This class is mean for:
 * Domain Driven Design
 * Activerecord
 */
abstract class Model {
    protected $db;
    
    /**
     * Create an object instance from a row (given as an associative array)          
     */
    abstract protected function validate();

    public function __construct($id) {
	$this->db = $conn;
	$this->table = static::TABLE;
    }

    public function save() {
        if ()
    }

    /**
     * Execute an SQL query and return it's results
     * Internally this method prepares a PDO statement, executes it and fetches all it's results
     * @param string $query
     * @param int $fetch_style One of PDO::FETCH_* constants
     * @return array All rows in the result set
     */
    protected function getResults($query, $parameters = array(), $fetch_style = \PDO::FETCH_ASSOC) {
	$st = $this->db->prepare($query);
	$st->execute($parameters);
	return $st->fetchAll($fetch_style);
    }
    
    /**
     * Get an object by it's id
     * Internally this method returns the first row of a "SELECT *" statment
     * against the TABLE constant value of the concrete DAO class
     * Should be overriden by the concrete DAO in order to return an instance of
     * the corresponding domain object
     * @param mixed $id
     * @param boolean $as_json
     * @param string $hash
     * @return array
     */
    public function getById($id, $as_json = false, &$hash = "") {
	$query = "SELECT * FROM {$this->table} WHERE id = :id";
	
	$result = $this->getResults($query, array('id' => $id));	
	if (count($result) == 0)
	    return false;
	
	$result[0]['hash'] = $this->getHash($result[0]);
	
	return $result[0];
    }
    
    /**
     * Save an object to the database
     * Performs an INSERT or an UPDATE depending of the getHash() method of the object
     * returning a value or not
     * Every PublicAttribute of the object is stored at the public_attribute column of the 
     * concrete DAO table
     * @param \Vamp\Core\Domain\VampDomain $object
     * @throws \Vamp\Core\Exception\DatabaseException If the object is not saved
     */
    public function save(\Vamp\Core\Domain\VampDomain $object) {	
	$object->validate();
	
	if($object->getHash()) {
	    return $this->update($object);
	} else {
	    return $this->insert($object);
	}
    }
    
    /**
     * Insert a new record at the concrete DAO table
     * Every attribute of the object is stored, matching it's snake_case converted name to
     * a table's column of the same name: objectAttribute is stored at column object_attribute
     * If any public attribute does not match a column name, then this method will fail
     * @param \Vamp\Core\Domain\VampDomain $object
     * @throws \Vamp\Core\Exception\DatabaseException If the insert can not be performed
     */
    private function insert(\Vamp\Core\Domain\VampDomain $object) {
	$values = array();	
	foreach (get_object_vars($object) as $column => $value) {
	    $values[$column] = $value;
	}
	unset($values['type']);
		
	$insert = 'INSERT INTO '.$this->table.' ('.implode(',',array_keys($values)).') VALUES ';
	$insert.='('.implode(',', array_map(array($this, 'paramFormatter'), array_keys($values))).')';
	
	$st = $this->db->prepare($insert);
	foreach ($values as $name => $value) {
	    $st->bindValue($this->paramFormatter($name), $value);	    
	}		
	
	if (!$st->execute())
	    throw \Vamp\API\Exception\DatabaseException::getInstance('Database error', 
		    array('code' => $st->errorCode(), 'message' => $st->errorInfo()));
	else {
	    $object->id = $this->db->lastInsertId();
	}
    }
    
    /**
     * Update a record at the concrete DAO table according to it's ID
     * Look at insert() for more details
     * @param \Vamp\Core\Domain\VampDomain $object
     * @throws \Vamp\Core\Exception\DatabaseException If the insert can not be performed
     */
    private function update(\Vamp\Core\Domain\VampDomain $object) {
	$values = array();	
	foreach (get_object_vars($object) as $name => $value) {
	    $values[$name] = $value;
	}
	unset($values['type']);
	unset($values['id']);
		
	$update = 'UPDATE '.$this->table.' SET ';
	$update_values = array();
	foreach ($values as $column => $value) {	    	    
	    $update_values[] = $column.' = '.$this->paramFormatter($column);
	}

	$update.= implode(', ', $update_values);
	$update.= ' WHERE id = :id';		
	
	$st = $this->db->prepare($update);
	$st->bindValue(':id', $object->id);	    
	foreach ($values as $name => $value) {
	    $st->bindValue($this->paramFormatter($name), $value);	    
	}		
	
	if (!$st->execute())
	    throw \Vamp\API\Exception\DatabaseException::getInstance('Database error', 
		    array('code' => $st->errorCode(), 'message' => $st->errorInfo()));	
    }
    
    /**
     * Delete a row by given ID
     * @param mixed $id
     * @throws \Vamp\Core\Exception\DatabaseException If the record can not be deleted
     */
    public function delete($id) {
	$st = $this->db->prepare('DELETE FROM '.$this->table.' WHERE id = :id');
	if (!$st->execute(array('id' => $id)))
	    throw \Vamp\API\Exception\DatabaseException::getInstance('Database error', 
		    array('code' => $st->errorCode(), 'message' => $st->errorInfo()));	
    }
    
    /**
     * Formats a parameter in order to be inserted at the prepared statement
     * so that it can be binded to a value later
     * @param string $columnName Actual column name
     * @return string Parameter name
     */
    protected function paramFormatter($columnName) {
	return ':'.$columnName;
    }

    /**
     * Translates a camel case string into a string with
     * underscores (e.g. firstName -> first_name)
     * @param string $str String in camel case format
     * @return string $str Translated into underscore format
     */
    protected function toSnakeCase($str) {
	$str[0] = strtolower($str[0]);
	$func = create_function('$c', 'return "_" . strtolower($c[1]);');
	return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    /**
     * Translates a string with underscores
     * into camel case (e.g. first_name -> firstName)
     * @param string $str String in underscore format
     * @param bool $capitalise_first_char If true, capitalise the first char in $str
     * @return string $str translated into camel caps
     */
    protected function toCamelCase($str, $capitalise_first_char = false) {
	if ($capitalise_first_char) {
	    $str[0] = strtoupper($str[0]);
	}
	$func = create_function('$c', 'return strtoupper($c[1]);');
	return preg_replace_callback('/_([a-z])/', $func, $str);
    }
    
    abstract protected function getSortableColumns();
    
    private function sortDirectionIsValid($direction) {
        return in_array(strtoupper(trim($direction)), array('ASC', 'DESC'));
    }
    
    protected function getOrderBySortString($sort_string, $default) {
        if (!$sort_string)
            return $default;
        $sortableColumns = $this->getSortableColumns();
        $sortStatements = explode(',', $sort_string);
        $orderBy = array();
        foreach($sortStatements as $ss) {
            list($column, $direction) = explode(':', $ss);
            
            if(!in_array($column, $sortableColumns))
                throw \Vamp\API\Exception\DatabaseException::getInstance('Wrong sort attribute', array($column));
            if(!$this->sortDirectionIsValid($direction))
                throw \Vamp\API\Exception\DatabaseException::getInstance('Wrong sort direction', array(trim($direction)));
            
            $orderBy[] = strtolower($column).' '.strtoupper(trim($direction));
        }
                        
        return implode(',', $orderBy);
    }
    
    protected function getHash($result) {
	return md5(implode(':', array_values($result)));
    }    

}
