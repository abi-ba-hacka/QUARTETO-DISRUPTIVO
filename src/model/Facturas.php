<?php 
class Validation {
	public function validate() {

	}
}
class Facturas extends Model {
	use SqlTableBucket;
	const NAME = 'Facturas';
	const PK = array('id_factura' => array(['integer'], ['min', 1]));
	const FIELDS = array(
		'fecha' => ['required', 'YYYY-mm-dd'],
		'cliente' => [
			'required',
			'integer', 
			['min', 1],
			['relation_to','titular']
		]
	);
	const RELATIONS_TO = array(
		'titular' => array(
			'fields' => array('nombre','apellido'),
			'target' => Clientes::NAME,
			'condition' => array(
				Clientes::NAME => 'id_cliente',
				Facturas::NAME => 'cliente'
			)
		)
	);
}