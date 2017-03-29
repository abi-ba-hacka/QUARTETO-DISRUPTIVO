<?php
function clienteList($id = null){
    $result = array();
    $result['id'] = $id;
    if ($id) {
        $st = Flight::db()->prepare('SELECT * FROM clientes WHERE id_cliente = :id;');
        $st->bindParam(':id', $id);
        $st->execute();
        $result['rows'] = $st->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $st = Flight::db()->prepare('SELECT * FROM clientes;');
        $st->execute();
        $result['rows'] = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    
    Flight::set('view','Clientes');
    Flight::set('result', $result);
    require_once 'src/response.php';
}
Flight::route('GET /clientes', clienteList);
Flight::route('GET /clientes/@id', clienteList);

function clienteForm($id = null){
    $cliente = null;
    $data = Flight::request()->data;
    ///print_r($data);exit;
    if ($id) {
        $st = Flight::db()->prepare('UPDATE users SET (
        nombre = :nombre, 
        apellido = :apellido, 
        dni = :dni, 
        telefono = :telefono,
        email = :email,
        direccion = :direccion)
        WHERE id_cliente = :id;');
        
        $st->bindParam(':id', $id);
        $st->bindParam(':nombre', $data['nombre']);
        $st->bindParam(':apellido', $apellido);
        $st->bindParam(':dni', $dni);
        $st->bindParam(':telefono', $telefono);
        $st->bindParam(':email', $email);
        $st->bindParam(':direccion', $direccion);
        $st->execute();
        echo $st->errorInfo();
    } else {
        $st = Flight::db()->prepare('INSERT INTO users (
            :nombre, 
            :apellido,
            :dni,
            :telefono, 
            :email, 
            :direccion
        );');
        
        $st->bindParam(':id', $id);
        $st->bindParam(':nombre', $data['nombre']);
        $st->bindParam(':apellido', $apellido);
        $st->bindParam(':dni', $dni);
        $st->bindParam(':telefono', $telefono);
        $st->bindParam(':email', $email);
        $st->bindParam(':direccion', $direccion);
    }
    Flight::set('redirect' ,'/clientes');
    require_once 'src/response.php';
}

Flight::route('POST|PUT /clientes/editar/@id', clienteForm);
Flight::route('POST|PUT /clientes/crear', clienteForm);

Flight::route('GET /facturas/', function(){
	$result = array();
    $facturas = Facturas::find('all');
    foreach ($facturas as $index => $factura) {
    	$result['rows'][] = $factura->to_array();
    	$cliente = Clientes::find_by_id_cliente($factura->cliente);
    }
    require_once 'src/response.php';
});
Flight::route('GET /facturas/@id', function($id){
	$result = array('headers'=>array('#', 'fecha', 'cliente', 'id_cliente'));
    $factura = Facturas::find_by_id_factura($id);
	$cliente = Clientes::find_by_id_cliente($factura->cliente);
	$items = Items::find_by_factura($id);

	$result = $factura->to_array();
	$result['items'] = $items->to_array();
    Flight::set('view','form');
    require_once 'src/response.php';
});