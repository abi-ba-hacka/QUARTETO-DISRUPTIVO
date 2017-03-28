<?php
Flight::route('GET /clientes', function(){
	$result = array();
    $clientes = Clientes::find('all');
    foreach ($clientes as $index => $cliente) {
    	$result['rows'][] = $cliente->to_array();
    }
    Flight::set('view','Clientes');
    Flight::set('result',$result);
});

Flight::route('POST|PUT /clientes/@id', function($id){
    $cliente = Clientes::create(array(
    	'nombre' => Flight::request()->data['nombre'],
    	'apellido' => Flight::request()->data['apellido'],
    	'dni' => Flight::request()->data['dni'],
    	'telefono' => Flight::request()->data['telefono'],
    	'email' => Flight::request()->data['email'],
    	'direccion' => Flight::request()->data['direccion']
    	)
    );
	$cliente->save();
	Flight::set('result',$cliente);
    Flight::set('view','form');
});
Flight::route('POST /clientes/@id', function($id){
    $cliente = Clientes::find_by_id_cliente($id);
    Flight::set('result',$cliente);
    Flight::set('view','form');
});

Flight::route('GET /facturas/', function(){
	$result = array();
    $facturas = Facturas::find('all');
    foreach ($facturas as $index => $factura) {
    	$result['rows'][] = $factura->to_array();
    	$cliente = Clientes::find_by_id_cliente($factura->cliente);
    }
    Flight::set('result',$result);
    Flight::set('view','Facturas');
});
Flight::route('GET /facturas/@id', function($id){
	$result = array('headers'=>array('#', 'fecha', 'cliente', 'id_cliente'));
    $factura = Facturas::find_by_id_factura($id);
	$cliente = Clientes::find_by_id_cliente($factura->cliente);
	$items = Items::find_by_factura($id);

	$result = $factura->to_array();
	$result['items'] = $items->to_array();
    Flight::set('result',$result);
    Flight::set('view','form');
});