<?php
Flight::route('/clientes', function(){
    App::$response['id'] = null;
    App::$response['data']['rows'] = Clientes::all();
    App::$response['view'] = 'Clientes';
});
Flight::route('/clientes/@id:[0-9]*', function($id){
    App::$response['id'] = $id;
    if (!$cliente = Clientes::get($id)) {
        Flight::notFound();
    } else {
        App::$response['data']['model'] = $cliente;
    }
    App::$response['view'] = 'Clientes';
});
Flight::route('/clientes/save', function() {
    App::$response['follow'] = App::url('clientes');
    $data = Flight::request()->data;
    $cliente = new Clientes($data['id_cliente']);
    $cliente->nombre = $data['nombre'];
	$cliente->apellido = $data['apellido'];
    $cliente->dni = $data['dni'];
    $cliente->telefono = $data['telefono'];
    $cliente->email = $data['email'];
    $cliente->direccion = $data['direccion'];
    $cliente->save();
});
Flight::route('/clientes/borrar/@id:[0-9]+', function($id) {
    App::$response['follow'] = App::url('clientes');
    $cliente = new Clientes($id);
    if ($cliente) {
        if ($cliente->drop()) {
            App::$flash_message->error('Se ha borrado correctamente #'.$id);
        } else {
            App::$flash_message->error('No se ha podido borrar #'.$id);
        }
    } else {
        App::$flash_message->error('No existe '.Clientes::TABLE.'/'.$id);
    }
});