<?php
$model = null;
$value_nombre = '';
$value_apellido = '';
$value_dni = '';
$value_telefono = '';
$value_email = '';
$value_direccion = '';

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

if ($model) {
	$value_nombre = 'value="'.$model['nombre'].'" ';
	$value_apellido = 'value="'.$model['apellido'].'" ';
	$value_dni = 'value="'.$model['dni'].'" ';
	$value_telefono = 'value="'.$model['telefono'].'" ';
	$value_email = 'value="'.$model['email'].'" ';
	$value_direccion = 'value="'.$model['direccion'].'" ';
}

?><div class="panel panel-default">
<div class="panel-heading">Clientes</div>
<div class="panel-body">
<form method="post" action="<?php echo App::url('clientes/save'); ?>">
	<input type="hidden" name="id" value="<?php echo App::$response['id'] ?>">
	<label>Nombre</label>
	<input type="text" name="nombre" <?php echo $value_nombre; ?> >
	<?php echo error_by_field('nombre'); ?>
	<br>
	<label>Apellido</label>
	<input type="text" name="apellido" <?php echo $value_apellido; ?>>
	<?php echo error_by_field('apellido'); ?>
	<br>
	<label>DNI</label>
	<input type="text" name="dni" <?php echo $value_dni; ?>>
	<?php echo error_by_field('dni'); ?>
	<br>
	<label>Teléfono</label>
	<input type="text" name="telefono" <?php echo $value_telefono; ?>>
	<?php echo error_by_field('telefono'); ?>
	<br>
	<label>Email</label>
	<input type="text" name="email" <?php echo $value_email; ?>>
	<?php echo error_by_field('email'); ?>
	<br>
	<label>Dirección</label>
	<input type="text" name="direccion" <?php echo $value_direccion; ?>>
	<?php echo error_by_field('direccion'); ?>
	<br>
	<input type="submit" name="">
</form>

<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
<tr>
<th>#</th>
<th>Nombre</th>
<th>Apellido</th>
<th>DNI</th>
<th>Teléfono</th>
<th>Email</th>
<th>Dirección</th>
<th>Aciones</th>
</tr>
</thead>
<tbody>
<?php 
if (isset(App::$response['data']['rows'])) {
	foreach (App::$response['data']['rows'] as $i => $row) { 
		echo '<tr class="'.($i%2==1?'odd':'even').' gradeX">';
		echo '<th>'.$row['id_cliente'].'</th>';
		echo '<th>'.$row['nombre'].'</th>';
		echo '<th>'.$row['apellido'].'</th>';
		echo '<th>'.$row['nombre'].'</th>';
		echo '<th>'.$row['telefono'].'</th>';
		echo '<th>'.$row['email'].'</th>';
		echo '<th>'.$row['direccion'].'</th>';
		echo '<th>';
		echo '<a href="'.BASE_URL.'clientes/borrar/'.$row['id_cliente'].'">borrar</a>';
		echo ' <a href="'.BASE_URL.'clientes/'.$row['id_cliente'].'">editar</a>';
		echo '</th>';
		echo '</tr>';
	}
}
?>
</tbody>
</table>
</div>
</div>