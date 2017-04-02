<?php
$form = new Form(
	App::url('items/save'),
	Items::FIELDS
);
$form->setModel(App::$response['data']['model']);
$form->addHiddenFields('id_item', App::$response['id']);
?>
<div class="panel panel-default">
<div class="panel-heading">Items</div>
<div class="panel-body"><?php
$form->render();
if (!App::$response['id']) {
	$datatable = new Datatable (
		App::$response['data']['rows'], 
		array_keys(Items::FIELDS), 
		function($row) {
			return  '<a href="'.App::url('items/'.$row['id_item']).'">Editar</a> <a href="'.App::url('items/'.$row['id_item']).'/borrar">Borrar</a>';
	});
	$datatable->render();
}
?></div>
</div>