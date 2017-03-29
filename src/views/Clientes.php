<h1>Clientes</h1>
<?php
$result = Flight::get('result');
//print_r($result);echo $result['id'];exit;
$form = null;
if ($result['id'] === null) {
	$form = new InputForm(BASE_URL.'clientes/crear');
} else {
	$form = new InputForm(BASE_URL.'clientes/editar/'.$id);
}
$form->addField('Nombre', new InputField('nombre', 'text', $result[0]['nombre']));
$form->addField('Apellido', new InputField('apellido','text', $result[0]['apellido']));
$form->addField('DNI', new InputField('dni','text', $result[0]['dni'] ));
$form->addField('Teléfono', new InputField('telefono','text', $result[0]['telefono']));
$form->addField('Email', new InputField('email','text', $result[0]['email']));
$form->addField('Dirección', new InputField('direccion','text', $result[0]['direccion']));

echo '<div class="col-md-4">';
$form->render();
echo '</div>';

if(isset($result['rows'])) { ?>
<div class="col-md-8">';
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
foreach ($result['rows'] as $i => $row) { 
	echo '<tr class="'.($i%2==1?'odd':'even').' gradeX">';
	echo '<th>#</th>';
	echo '<th>'.$row['nombre'].'</th>';
	echo '<th>'.$row['apellido'].'</th>';
	echo '<th>'.$row['nombre'].'</th>';
	echo '<th>'.$row['telefono'].'</th>';
	echo '<th>'.$row['email'].'</th>';
	echo '<th>'.$row['direccion'].'</th>';
	echo '<th>';
	echo '<a href="'.BASE_URL.'clientes/editar/'.$row['id_cliente'].'">editar</a> ';
	echo '<a href="'.BASE_URL.'clientes/borrar/'.$row['id_cliente'].'">borrar</a>';
	echo '</th>';
echo '</tr>';
}
?>
</tbody>
</table>
</div>';

<?php } ?>