<?php
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