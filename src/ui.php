<?php
function error_by_field($field) {
	$result = '';
	if (isset($_SESSION['errors'][$field])) {
		foreach (($_SESSION['errors'][$field]) as $key => $value) {
			$result .= $value.' ';
			unset($_SESSION['errors'][$field]);
		}
	}
	return $result;
}
class Layout {
	protected $title = '';
	public function __construct() {

	}
	public function addStylesheetURL($url) {
		$this->stylesheets[] = $url;
	}
	public function addJavascriptURL($url) {
		$this->scripts[] = $url;
	}
	public function render() { 
		echo '<!DOCTYPE html>';
		echo '<html lang="es">';
		echo '<head>';
		echo '<meta charset="utf-8">';
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		echo '<meta name="description" content="">';
		echo '<meta name="author" content="">';
		echo "<title>{$this->title}</title>";
		foreach ($this->stylesheets as $stylesheet) {
			echo "<link href='{$stylesheet}' rel='stylesheet'>";
		}
		echo '<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->';
		echo '</head>';
		echo '<body>';
		echo '<div id="wrapper">';
		echo '<nav class="navbar navbar-default navbar-static-top" role="navigation">';
		echo '<ul class="nav navbar-top-links navbar-right">';
		echo '<li><a href="'.App::url('clientes').'">Clientes</a></li>';
		echo '<li><a href="'.App::url('facturas').'">Facturas</a></li>';
		echo '<li><a href="'.App::url('Items').'">items</a></li>';
		echo '</ul>';
		echo '</nav>';
		App::$flash_message->display();
		echo '<div id="page-wrapper">';
	    echo '<div class="panel-body">';

	    if ($view = App::$response['view']) {
			require_once 'src/views/'.App::$response['view'].'.php';
		}

	    echo '</div>';
	    echo '</div>';
	    echo '</div>';

		foreach ($this->scripts as $script) {
			echo "<script src='{$script}'></script>";
		}
		echo '</body>';
		echo '</html>';
	}
}
class Form {
	private $hiddenFields = array();
	public function __construct($action, $fields=array()) {
		$this->action = $action;
		$this->fields = $fields;
	}
	public function setModel($model) {
		$this->model = $model;
	}
	public function setErrors($errors) {
		$this->errors = $errors;
	}
	public function addHiddenFields($name, $value) {
		$this->hiddenFields[$name] = $value;
	}
	public function render() {
		echo '<form method="post" action="'.App::url('facturas/save').'">';
		foreach ($this->fields as $name => $field) {
			echo '<label>';
			echo App::i18n($name);
			echo '</label>';
			echo '<input name="'.$name.'"';
			if (in_array('date', $field)) echo 'type="date"';
			else echo 'type="text"';
			echo '/>';
			echo '<br>';
		}
		foreach ($this->hiddenFields as $name => $value) {
			echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
		}
		echo '<input type="reset" name="">';
		echo '<input type="submit" name="">';
		echo '</form>';
	}
}