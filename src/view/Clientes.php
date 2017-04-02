<?php
$form = new Form(
	App::url('clientes/save'),
	Clientes::FIELDS
);
$form->setModel(App::$response['data']['model']);
$form->addHiddenFields('id_cliente', App::$response['id']);
?>
<div class="panel panel-default">
<div class="panel-heading">Clientes</div>
<div class="panel-body"><?php
$form->render();
if (!App::$response['id']) {
	$datatable = new Datatable (
		App::$response['data']['rows'], 
		array_keys(Clientes::FIELDS), 
		function($row) {
			return  '<a href="'.App::url('clientes/'.$row['id_cliente']).'">Editar</a> <a href="'.App::url('clientes/'.$row['id_cliente']).'/borrar">Borrar</a>';
	});
	$datatable->render();
}
?></div>
</div>