<?php
class Form {
	private $model;
	private $action;
	private $fields = array();
	private $hiddenFields = array();
	public function __construct($action, $fields=array()) {
		$this->action = $action;
		$this->fields = $fields;
	}
	public function setModel($model) {
		$this->model = $model;
	}
	public function addHiddenFields($name, $value) {
		$this->hiddenFields[$name] = $value;
	}
	public function render() {
		if (App::$referral_request) {
			foreach ($this->fields as $name => $field) {
				$this->model[$name] = App::$referral_request[$name];
			}	
		}
		echo '<form method="post" action="'.$this->action.'">';
		foreach ($this->fields as $name => $field) {
			echo '<label>';
			echo App::i18n($name);
			echo '</label>';
			echo '<input name="'.$name.'"';
			if (in_array('YYYY-mm-dd', $field)) echo 'type="date"';
			else echo 'type="text"';
			if (in_array('required', $field)) echo 'required="required"';
			if (isset($this->model[$name])) {
				echo ' value="'.$this->model[$name].'" ';
			}
			echo '/> ';
			if (in_array('required', $field)) echo '*';
			if (isset($_SESSION['errors']['form'][$name])) {
				echo implode(' ', $_SESSION['errors']['form'][$name]);
				unset($_SESSION['errors']['form'][$name]);
			}
			echo '<br>';
		}
		foreach ($this->hiddenFields as $name => $value) {
			echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
		}
		echo '<input type="reset" name=""> <input type="submit" name="">';
		echo '</form>';
	}
}