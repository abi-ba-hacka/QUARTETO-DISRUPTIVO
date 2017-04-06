<?php 
class Clientes extends Model {
	use SqlTableBucket;
	const NAME = 'Clientes';
	const PK = array('id_cliente' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'nombre' => ['required'],
		'apellido' => ['required'],
		'dni' => ['required'],
		'telefono' => ['required'],
		'email' => ['required','email'],
		'direccion' => ['required']
	);
	const SELECTION = array(
		'nombre_completo' => array(
			'fields' => array('nombre','apellido')
		)
	);
}