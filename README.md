# Facaturacion

It is a simple architecture for php webapps that joins some 3rd party components.
- MVC Framework: "mikecao/flight"
- Validator: "vlucas/valitron"
- Collection: "maciejczyzewski/bottomline"
- Logger: "monolog/monolog"
- Flash messages: "plasticbrain/php-flash-messages"
- Responsive UI: jQuery, Twitter Bootstrap & SBadmin2 Template

## Install 

Download source at your webserver directory. 
Install dependencies by running composer install at src/
Edit config.php with your database connection data:
```php
return array(
  'pdo_driver' => 'mysql',
  'pdo_user' => 'user',
  'pdo_password' => 'password',
  'pdo_host' => 'localhost',
  'pdo_database_name' => 'main' 
);
```
## Use & Custom

You can create CRUDs like the default samples included. Modify files at:
src/model/
src/controller/
src/view/

Map each model with your database tables. You can also make some validations. Check out [vlucas/valitron]: http://github.com/vlucas/valitron
for more info about validator.
```php
class Facturas extends Model {
  use SqlTableBucket;
  const TABLE = 'Facturas';
  const PK = array('id_factura' => array(['integer'], ['min', 1]));
  const FIELDS = array(
    'fecha' => ['required', 'YYYY-mm-dd'],
    'cliente' => ['required']
  );
}
```
You can make the clasic CRUD controller methods like in the samples or something else you need. Check out [Flightphp.com]: http://flightphp.com
for more info about MVC framework.
You can use your controlles by AJAX consuming JSON responses.

```php
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
    $factura->fecha = $data['fecha'];
    $factura->cliente = $data['cliente'];    
    if (!$factura->save() && $data->pk) {
        App::$response['follow'] = App::url('facturas/'.$data->pk);
    }
});
```
Then use a view to show the info. You can use Form and Datable components for your user interface.

```php
$form = new Form(
  App::url('facturas/save'),
  Facturas::FIELDS
);
$form->setModel(App::$response['data']['model']);
$form->addHiddenFields('id_factura', App::$response['id']);
?>
<div class="panel panel-default">
<div class="panel-heading">Facturas</div>
<div class="panel-body"><?php
$form->render();
if (!App::$response['id']) {
  $datatable = new Datatable (
    App::$response['data']['rows'], 
    array_keys(Facturas::FIELDS), 
    function($row) {
      return  '<a href="'.App::url('facturas/'.$row['id_factura']).'">Editar</a> <a href="'.App::url('facturas/'.$row['id_factura']).'/borrar">Borrar</a>';
  });
  $datatable->render();
}
?></div>
</div>
```

Comming soon Features
- i18n (Multi-Languaje)
- Automatic database tables creation from php Models

made love with in Argentina