<?php
class Form {
	protected $model;
	protected $action;
	protected $fields = array();
	protected $options =array();
	protected $hiddenFields = array();
	public function __construct($action,$fields=array()) {
		$this->action = $action;
		$this->fields = $fields;
	}
	public function setModel($model) {
		$this->model = $model;
	}
	public function addHiddenFields($name, $value) {
		$this->hiddenFields[$name] = $value;
	}
	public function setOptions($name, $options = array(), $fn) {
		$this->options[$name] = $options;
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
			if (isset($this->options[$name])) {
				echo '<select>';
				foreach ($this->options[$name] as $option) {
					echo '<option value="'.$option['id_cliente'].'">';
					echo $option['apellido'].' '.$option['nombre'];
					echo '</option>';
				}
				echo '</select>';
			} else {
				echo '<input name="'.$name.'"';
				if (in_array('YYYY-mm-dd', $field)) {
					$type = 'date';	
				} else {
					if (in_array('integer', $field)) {
						$type = 'number';
						echo ' step="1" ';
					} elseif (in_array('number', $field)) {
						$type = 'number';
					} else {
						$type = 'text';
					}
				}
				echo 'type="'.$type.'"';
				if (in_array('required', $field)) echo 'required="required"';
				if (isset($this->model[$name])) {
					echo ' value="'.$this->model[$name].'" ';
				}
				
				foreach ($field as $validation) {
					if (is_array($validation) && $validation[0] == 'min') {
						echo ' min="'.$validation[1].'" ';
					}
				}
				echo '/> ';
				if (in_array('required', $field)) echo '*';
				if (isset($_SESSION['errors']['form'][$name])) {
					echo implode(' ', $_SESSION['errors']['form'][$name]);
					unset($_SESSION['errors']['form'][$name]);
				}	
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