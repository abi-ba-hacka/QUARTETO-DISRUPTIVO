<?php
require_once 'src/vendor/autoload.php';
require_once 'src/ABSPATH.php';
require_once 'src/vendor/autoload.php';
require_once 'src/core/Model.php';
require_once 'src/models/Facturas.php';
require_once 'src/models/Items.php';
require_once 'src/models/Clientes.php';
require_once 'src/actions/Clientes.php';
require_once 'src/actions/Facturas.php';
require_once 'src/actions/Items.php';

ob_start();
if (!session_id()) @session_start();
use Underscore\Types\Arrays;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class App {
	static $development = true;
	public static $db;
	public static $flash_message;
	public static $redirect;
	public static $response = array ();
	public function pushRequestError($field, $description) {
		$_SESSION['errors'][$field][] = $description;
	}
	public function resetRequestErrors() {
		$_SESSION['errors'] = array();
	}
	public static function init() {
		static::$db = new PDO('mysql:host=localhost;dbname=Facturacion;charset=utf8mb4', 'root', '');
		static::$flash_message = new \Plasticbrain\FlashMessages\FlashMessages();
	}
	public function setAction($action) {
		static::$response['action'] = $action;
	}
	public static function url($path) {
		return BASE_URL.$path;
	}
	public function debug($info,$name=null) {
		App::$response['trace'][] = array($name=>$info);
	}
	public function i18n($key,$params = null) {
		return $key;
	}
}

App::init();
class InputException extends Exception {
	public function __construct() {
		$_SESSION['request'] = $_REQUEST;
		parent::__construct();
	}
}

$render = function(&$params, &$output){
	if (App::$development) {
		App::$response['__session'] = $_SESSION;
		App::$response['__post'] = $_POST;
	}
	if (Flight::request()==='ajax' || isset($_GET['ajax'])) {
		App::$response['flash_message'] = $_SESSION['flash_messages'];
		App::$response['errors'] = $_SESSION['errors'];
		echo Arrays::from(App::$response)->toJSON();
		unset($_SESSION['flash_messages']);
		unset($_SESSION['errors']);
	} else {
		if (isset(App::$response['follow'])) {
			if (App::$development) {
				echo '<a href="'.App::$response['follow'].'">Redirect</a>';
			} else {
				Flight::redirect($redirect);
			}
		} else {
			require_once 'src/ui.php';
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
		if (App::$development) {
	    	echo '<pre style="width:100%;white-space: pre-wrap;">';
	    	print_r(App::$response);
	    	// echo 'PARAMS: ';print_r($params);
			// echo 'OUTPUT: ';print_r($output);
	    	echo '</pre>';
	    }
	}
};

Flight::route('GET /', function(){});
Flight::before('start', function(){
	App::setAction(Flight::request()->url);
});
Flight::after('start', $render);

$a=array();
try {
	Flight::start();
} catch(Exception $e) {
	if (App::$development) {
		App::$response['internal error'] = $e;	
	} else {
		App::$flash_message->error('Se ha producido un error');
	}
	$render($a,$a);
}
