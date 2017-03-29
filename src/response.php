<?php
if (Flight::request()==='ajax') {
	echo Arrays::from($result)->toJSON();
} else {
	//if (Flight::get('redirect')) Flight::redirect(Flight::get('redirect'));
	require_once 'src/ui.php';
	$ui = new Layout();
	$ui->setContentView(Flight::get('view'));
	$ui->addStylesheetURL(BASE_URL.'vendor/bootstrap/css/bootstrap.min.css');
	$ui->addStylesheetURL(BASE_URL.'vendor/metisMenu/metisMenu.min.css');
	$ui->addStylesheetURL(BASE_URL.'dist/css/sb-admin-2.css');
	$ui->addStylesheetURL(BASE_URL.'vendor/morrisjs/morris.css');
	$ui->addStylesheetURL(BASE_URL.'vendor/font-awesome/css/font-awesome.min.css');

	$ui->addJavascriptURL(BASE_URL.'vendor/jquery/jquery.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/datatables/js/jquery.dataTables.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/datatables-plugins/dataTables.bootstrap.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/datatables-responsive/dataTables.responsive.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/bootstrap/js/bootstrap.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/metisMenu/metisMenu.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/raphael/raphael.min.js');
	$ui->addJavascriptURL(BASE_URL.'vendor/morrisjs/morris.min.js');
	$ui->addJavascriptURL(BASE_URL.'dist/js/sb-admin-2.js');
	$ui->addJavascriptURL(BASE_URL.'js/main.js');

	$ui->render();

}