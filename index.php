<?php
require_once 'src/vendor/autoload.php';
require_once 'src/ABSPATH.php';
require_once 'src/vendor/autoload.php';

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

if (isset($_SESSION['referral_request'])) {
	App::$referral_request = $_SESSION['referral_request'];
	unset($_SESSION['referral_request']);
}

class App {
	static $development = false;
	public static $referral_request;
	public static $db;
	public static $flash_message;
	public static $redirect;
	public static $internal_error;
	public static $response = array (
		'action' => null,
		'data'=> array(
			'id' => null,
			'rows' => null,
			'model' => null,
		),
		'view' => null
	);
	public static function init() {
		

		$config = require_once 'src/config.php';
		static::$db = new PDO(
			$config['pdo_driver'].':host='.	$config['pdo_host'].
			';dbname='.$config['pdo_database_name'].
			';charset=utf8mb4', 'root', '');
		static::$flash_message = new \Plasticbrain\FlashMessages\FlashMessages();
		$log = new Logger('name');
		$log->pushHandler(new StreamHandler('/tmp/php_error.log', Logger::WARNING));
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
	public function redirect($url) {
		if (App::$development) {
			echo '<a href="'.App::$response['follow'].'">Redirect</a>';
		} else {
			Flight::redirect(App::$response['follow']);
		}
	}
	public function info() {
		if (App::$development) {
			echo '<!--';
			echo 'SESSION: ';print_r($_SESSION);
			echo 'POST: ';print_r($_POST);
			echo 'RESPONSE: ';print_r(App::$response);
			echo 'INTERNAL_ERROR: ';print_r(App::$internal_error);
			echo 'DATABASE_ERROR_INFO: ';print_r(App::$db->errorInfo());
			echo '-->';
		}
	}
}
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