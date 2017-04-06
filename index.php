<?php
require_once 'src/vendor/autoload.php';
require_once 'src/ABSPATH.php';
require_once 'src/vendor/maciejczyzewski/bottomline/bottomline.php';

require_once 'src/core/App.php';
require_once 'src/core/Model.php';
require_once 'src/core/SqlTableBucket.php';

require_once 'src/model/Facturas.php';
require_once 'src/model/Items.php';
require_once 'src/model/Clientes.php';

require_once 'src/controller/Clientes.php';
require_once 'src/controller/Facturas.php';
require_once 'src/controller/Items.php';

require_once 'src/core/HtmlNode.php';
require_once 'src/core/Layout.php';
require_once 'src/core/Form.php';
require_once 'src/core/Datatable.php';

ob_start();
if (!session_id()) @session_start();
use Underscore\Types\Arrays;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

App::init();
class InputException extends Exception {
	public function __construct() {
		$_SESSION['request'] = $_REQUEST;
		parent::__construct();
	}
}
Flight::route('GET /', function(){});
Flight::before('start', function(){
	App::setAction(Flight::request()->url);
});
Flight::after('start', function(&$params, &$output){
	if (Flight::request()==='ajax' || isset($_GET['ajax'])) {
		App::$response['flash_message'] = $_SESSION['flash_messages'];
		App::$response['errors'] = $_SESSION['errors'];
		echo Arrays::from(App::$response)->toJSON();
		unset($_SESSION['flash_messages']);
		unset($_SESSION['errors']);
	} else {
		if (isset(App::$response['follow'])) {
			App::redirect(App::$response['follow']);
		} else {
			$ui = new Layout();
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
		App::info();
	}
});
try {
	Flight::start();
} catch(Exception $e) {
	App::$internal_error = $e;	
	App::$flash_message->error('Se ha producido un error');
}
App::info();