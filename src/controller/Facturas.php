<?php
Flight::route('/facturas', function(){
    App::$response['id'] = null;
    App::$response['data']['rows'] = Facturas::rows();
    App::$response['view'] = 'Facturas';
});
Flight::route('/facturas(/@id:[0-9]*)', function($id) {
    App::$response['id'] = $id;
    if (!$factura = Facturas::get($id)) {
        Flight::notFound();
    } else {
        App::$response['data']['model'] = $factura;
    }
    App::$response['view'] = 'Facturas';
});
Flight::route('/facturas/save', function() {
    App::$response['follow'] = App::url('facturas');
    $data = Flight::request()->data;
    $factura = new Facturas($data['id_factura']);
    $factura->fromArray($data);    
    if (!$factura->save() && $data->pk) {
        App::$response['follow'] = App::url('facturas/'.$data->pk);
    }
});
Flight::route('/facturas/@id:[0-9]+/borrar', function($id_factura) {
    App::$response['follow'] = App::url('facturas');
    try {
        echo Facturas::destroy($id_factura)?'ok':'no';
    } catch (Exception $e) {
        App::$response['internal_error'] = $e;
        App::$flash_message->error('No se puede borrar este factura sin antes borrar las facturas asociadas');
    }
});