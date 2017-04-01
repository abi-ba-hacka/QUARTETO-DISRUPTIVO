<?php
Flight::route('GET /items', function(){
    $result = array();
    $items = Items::find('all');
    foreach ($items as $index => $item) {
        $result['rows'][] = $item->to_array();
    }
    Flight::set('view','Items');
    Flight::set('result',$result);
});

Flight::route('GET /items/@id', function($id_item){
    Items::validateId($id_item);
    $result = array();
    if (!$item = Items::find_by_id_item($id_item)) {
        Flight::notFound();
    }
    
    $result['rows'][] = $item->to_array();
    $result['id_item'] = $id_item;
    Flight::set('view','Items');
    Flight::set('result',$result);
});

Flight::route('POST|PUT /items/editar/@id_item', function($id_item){
    Flight::set('redirect',BASE_URL.'items');
    $data = Flight::request()->data;
    Items::validateId($id_item);
    Items::validateFields($data);
    $item = Items::find_by_id_item($id_item);
    $item->factura = $data['factura'];
    $item->producto = $data['producto'];
    $item->cantidad = $data['cantidad'];
    $item->costo_unitario = $data['costo_unitario'];
    $item->impuestos = $data['impuestos'];
    $item->costo_total = $data['costo_total'];
    $item->save();
});
Flight::route('POST|PUT /items/crear', function(){
    Flight::set('redirect',BASE_URL.'items');
    $data = Flight::request()->data;
    Items::validateFields($data);
    Items::create(array(
        'factura' => Flight::request()->data['factura'],
        'producto' => Flight::request()->data['producto'],
        'cantidad' => Flight::request()->data['cantidad'],
        'costo_unitario' => Flight::request()->data['costo_unitario'],
        'impuestos' => Flight::request()->data['impuestos'],
        'costo_total' => Flight::request()->data['costo_total']
        )
    );
});
Flight::route('GET|DELETE /items/borrar/@id_item', function($id_item) {
    Flight::set('redirect',BASE_URL.'items');
    $item = Items::find_by_id_item($id_item);
    try {
        $item->delete();    
    } catch (Exception $e) {
        App::$flash_message->error('No se puede borrar este item sin antes borrar las facturas asociadas');
    }
});