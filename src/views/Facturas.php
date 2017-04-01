<?php
$model = null;
$value_fecha = '';
$value_cliente = '';

if (App::$response['id']) {
	$model = App::$response['data']['model'];
	if (isset($_SESSION['request'])) {
		$model = $_SESSION['request'];
		unset($_SESSION['request']);
	}
} else {
	if (isset($_SESSION['request'])) {
		$model = $_SESSION['request'];
		unset($_SESSION['request']);
	}
	if (isset($_SESSION['errors'])) {
		$model = $_REQUEST;
	}
}


$form = new Form(
	App::url('facturas/save'),
	Facturas::FIELDS
);
$form->setModel($model);
$form->addHiddenFields('id_factura', App::$response['id']);


?><div class="panel panel-default">
<div class="panel-heading">Clientes</div>
<div class="panel-body">
<?php $form->render(); ?>

<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
<tr>
<th>#</th>
<th>Fecha</th>
<th>Cliente</th>
</tr>
</thead>
<tbody>
<?php 
if (isset(App::$response['data']['rows'])) {
	foreach (App::$response['data']['rows'] as $i => $row) { 
		echo '<tr class="'.($i%2==1?'odd':'even').' gradeX">';
		echo '<th>'.$row['id_cliente'].'</th>';
		echo '<th>'.$row['fecha'].'</th>';
		echo '<th>';
		echo '<a href="'.BASE_URL.'facturas/borrar/'.$row['id_cliente'].'">borrar</a>';
		echo ' <a href="'.BASE_URL.'facturas/'.$row['id_cliente'].'">editar</a>';
		echo '</th>';
		echo '</tr>';
	}
}
?>
</tbody>
</table>
</div>
</div>