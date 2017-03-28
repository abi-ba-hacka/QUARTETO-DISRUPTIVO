<?php
require_once 'src/vendor/autoload.php';
require_once 'src/ABSPATH.php';
require_once 'src/vendor/autoload.php';
require_once 'src/models/Facturas.php';
require_once 'src/models/Items.php';
require_once 'src/models/Clientes.php';
require_once 'src/actions/admin.php';
ob_start();
if (!session_id()) @session_start();
use Underscore\Types\Arrays;

ActiveRecord\Config::initialize(function($cfg) {
	$config = require_once "src/config.php";
    $cfg->set_model_directory('src/models');
    $cfg->set_connections(array(
         'development' => "mysql://{$config['pdo_user']}:{$config['pdo_password']}@{$config['pdo_host']}/{$config['pdo_database_name']}?charset=utf8"));
});

Flight::route('GET /', function(){});
Flight::after('start', function(&$params, &$output){
	$result = Flight::get('result');
	if (Flight::request()==='ajax') {
		echo Arrays::from($result)->toJSON();
	} else {
		if ($redirect = Flight::get('redirect') || Flight::request()->method != 'GET') {
			$_SESSION['result'] = $result;
			Flight::redirect($redirect?$redirect:'.');
		} else {
			require_once 'src/ui.php';
			if (isset($_SESSION['result'])){
				$result = $_SESSION['result'];
				unset($_SESSION['result']);
			}
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
	}
});


Flight::start();