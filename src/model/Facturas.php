<?php 
class Facturas extends Model {
	use SqlTableBucket;
	const TABLE = 'Facturas';
	const PK = array('id_factura' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'fecha' => ['required', 'YYYY-mm-dd'],
		'cliente' => ['required','integer',['min', 1]]
	);
}