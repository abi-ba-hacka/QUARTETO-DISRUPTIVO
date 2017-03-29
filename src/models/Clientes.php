<?php 
class Clientes extends ActiveRecord\Model{

	static $validates_format_of = array(
    	//array('email', 'with' => '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/')
    	//,array('password', 'with' => '/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/', 'message' => 'is too weak')
 	);
	static $validates_presence_of = array(
		//array('nombre', 'apellido', 'dni', 'telefono', 'email', 'email', 'direccion')
    );
}
