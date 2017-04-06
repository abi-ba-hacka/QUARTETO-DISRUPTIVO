<?php 
class Items extends Model {
	use SqlTableBucket;
	const NAME = 'Items';
	const PK = array('id_item' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'factura' => ['required'],
		'producto' => ['required'],
		'cantidad' => ['required'],
		'costo_unitario' => ['required'],
		'impuestos' => ['required'],
		'costo_total' => ['required']
	);
}