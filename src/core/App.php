<?php
class App {
	protected static $development = true;
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
		if (isset($_SESSION['referral_request'])) {
			App::$referral_request = $_SESSION['referral_request'];
			unset($_SESSION['referral_request']);
		}

		$config = require_once 'src/config.php';
		static::$db = new PDO(
			$config['pdo_driver'].':host='.	$config['pdo_host'].';dbname='.$config['pdo_database_name'].';charset=utf8mb4', $config['pdo_user'], $config['pdo_password']);
		static::$flash_message = new \Plasticbrain\FlashMessages\FlashMessages();
		// $log = new Logger('name');
		// $log->pushHandler(new StreamHandler('/tmp/php_error.log', Logger::WARNING));
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