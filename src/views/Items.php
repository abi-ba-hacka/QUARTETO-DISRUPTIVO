<?php
$result = Flight::get('result');
$action = BASE_URL.'items/';
$model = null;
$value_factura = '';
$value_producto = '';
$value_cantidad = '';
$value_costo_unitario = '';
$value_impuestos = '';
$value_costo_total = '';

if (isset($result['id_item'])) {
	$action .= 'editar/'.$result['id_item'];
	$model = $result['rows'][0];
	if (isset($_SESSION['request'])) {
		$model = $_SESSION['request'];
		unset($_SESSION['request']);
	}
} else {
	$action .= 'crear';
	if (isset($_SESSION['request'])) {
		$model = $_SESSION['request'];
		unset($_SESSION['request']);
	}
	if (isset($_SESSION['errors'])) {
		$model = $_REQUEST;
	}
}

if ($model) {
	$value_factura = 'value="'.$model['factura'].'" ';
	$value_producto = 'value="'.$model['producto'].'" ';
	$value_cantidad = 'value="'.$model['cantidad'].'" ';
	$value_costo_unitario = 'value="'.$model['costo_unitario'].'" ';
	$value_impuestos = 'value="'.$model['impuestos'].'" ';
	$value_costo_total = 'value="'.$model['costo_total'].'" ';
}

?><div class="panel panel-default">
<div class="panel-heading">Items</div>
<div class="panel-body">
<form method="post" action="<?php echo $action; ?>">
	<label>Factura</label>
	<input type="text" name="factura" <?php echo $value_factura; ?> >
	<?php echo error_by_field('factura'); ?>
	<br>
	<label>Producto</label>
	<input type="text" name="producto" <?php echo $value_producto; ?>>
	<?php echo error_by_field('producto'); ?>
	<br>
	<label>Cantidad</label>
	<input type="text" name="cantidad" <?php echo $value_cantidad; ?> >
	<?php echo error_by_field('cantidad'); ?>
	<br>
	<label>Costo unitarios</label>
	<input type="text" name="costo_unitario" <?php echo $value_costo_unitario; ?>>
	<?php echo error_by_field('costo_unitario'); ?>
	<br>
	<label>Impuestos</label>
	<input type="text" name="impuestos" <?php echo $value_impuestos; ?> >
	<?php echo error_by_field('impuestos'); ?>
	<br>
	<label>Costo total</label>
	<input type="text" name="costo_total" <?php echo $value_costo_total; ?>>
	<?php echo error_by_field('costo_total'); ?>
	<br>
	<input type="submit" name="">
</form>
<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
<tr>
<th>#</th>
<th>Factura</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Costo unitarios</th>
<th>Impuestos</th>
<th>Costo total</th>
</tr>
</thead>
<tbody>
<?php foreach ($result['rows'] as $i => $row) { 
	echo '<tr class="'.($i%2==1?'odd':'even').' gradeX">';
	echo '<th>'.$row['factura'].'</th>';
	echo '<th>'.$row['producto'].'</th>';
	echo '<th>'.$row['cantidad'].'</th>';
	echo '<th>'.$row['costo_unitario'].'</th>';
	echo '<th>'.$row['impuestos'].'</th>';
	echo '<th>'.$row['costo_total'].'</th>';
	echo '<th>';
	echo '<a href="'.BASE_URL.'items/'.$row['id_item'].'/borrar/'.$row['id_item'].'">borrar</a>';
	echo ' <a href="'.BASE_URL.'items/'.$row['id_item'].'/editar">editar</a>';
	echo '</th>';
	echo '</tr>';
}
?>
</tbody>
</table>
</div>
</div>