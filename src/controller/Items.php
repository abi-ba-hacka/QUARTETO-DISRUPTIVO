<?php
Flight::route('/items', function(){
    App::$response['id'] = null;
    App::$response['data']['rows'] = Items::all();
    App::$response['view'] = 'Items';
});
Flight::route('/items(/@id:[0-9]*)', function($id) {
    App::$response['id'] = $id;
    if (!$item = Items::get($id)) {
        Flight::notFound();
    } else {
        App::$response['data']['model'] = $item;
    }
    App::$response['view'] = 'Items';
});
Flight::route('/items/save', function() {
    App::$response['follow'] = App::url('items');
    $data = Flight::request()->data;
    $item = new Items($data['id_item']);
    $item->fromArray($data);
    if (!$item->save() && $data->pk) {
        App::$response['follow'] = App::url('items/'.$data->pk);
    }
});
Flight::route('/items/@id:[0-9]+/borrar', function($id_item) {
    App::$response['follow'] = App::url('items');
    try {
        echo Items::destroy($id_item)?'ok':'no';
    } catch (Exception $e) {
        App::$response['internal_error'] = $e;
        App::$flash_message->error('No se puede borrar este item sin antes borrar las items asociadas');
    }
});