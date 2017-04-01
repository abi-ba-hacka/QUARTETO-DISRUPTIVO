<?php 
class Facturas extends Model {
	protected $id_factura;
	const TABLE = 'Facturas';
	const PK = array('id_factura' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'fecha' => ['required', 'date'],
		'cliente' => ['required']
	);
}