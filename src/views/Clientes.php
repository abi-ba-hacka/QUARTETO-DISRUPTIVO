<div class="panel panel-default">
<div class="panel-heading">Clientes</div>
<div class="panel-body"><?php
$result = Flight::get('result');

$form = new InputForm('editar');
$form->addField(new InputField('nombre','text'));
$form->addField(new InputField('appellido','text'));
$form->addField(new InputField('dni','text'));
$form->addField(new InputField('telefono','text'));
$form->addField(new InputField('email','text'));
$form->addField(new InputField('direccion','text'));
$form->render();

if(isset($result['rows'])) {
?>
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
	echo '<th><a href="'.BASE_URL.'clientes/borrar/'.$row['id_cliente'].'">borrar</a></th>';
echo '</tr>';
}
?>
</tbody>
</table>
</div>
</div>
<?php } ?>