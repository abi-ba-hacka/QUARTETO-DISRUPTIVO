<?php
class Layout {
	protected $title = '';
	protected $content_view = null;
	public function __construct() {

	}
	public function addStylesheetURL($url) {
		$this->stylesheets[] = $url;
	}
	public function addJavascriptURL($url) {
		$this->scripts[] = $url;
	}
	public function setContentView($content_view) {
		$this->content_view = $content_view;
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
		if (isset($_SESSION['valid']) && $_SESSION['valid'] === true) {
			echo '<div id="wrapper">';
			//include 'layout_nav.phtml';
			echo '<div id="page-wrapper">';
		    echo '<div class="panel-body">';
		    
		    if ($this->content_view) {
				require_once "src/views/{$this->content_view}.php";
			}
		    
		    echo '</div>';
		    echo '</div>';
		    echo '</div>';
		} else { 
			//include 'layout_login_body.phtml';
		}
		foreach ($this->scripts as $script) {
			echo "<script src='{$script}'></script>";
		}
		echo '</body>';
		echo '</html>';
	}
}

class InputForm {
	protected $fields = array();
	protected $method = 'post';
	protected $action = '.';
	public function __construct($action) {
		$this->action = $action;
	}
	public function addField($inputField) {
		$this->fields[] = $inputField;
	}
	public function render() {
		echo '<form method="'.$this->method.'" action="'.$this->action.'">';
		foreach ($this->fields as $index => $field) {
			$field->render();
		}
		echo '</form>';
	}
}
class InputField {
	protected $type;
	protected $value;
	protected $name;
	protected $validation;
	protected $required;
	public function __construct() {

	}
	public function render() {
		echo "<input type='{$this->type}' name='{$this->name}' value='{$this->value}' {($this->required?'required=\"required\"':'')} />"; 
	}
}

class DataTable  {
    function render() {
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">'.$this->title.'</div>';
        echo '<div class="panel-body">';
        echo '<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">';
        if ($headers = $this->headers) {
            echo '<thead><tr>';
            foreach ($headers as $key => $value) {
                echo '<th>'.$value.'</th>';
            }
            echo '</tr></thead>';
        }
        echo '<tbody>';
        foreach ($this->rows as $i => $row) {
            echo '<tr class="'.($i%2==1?'odd':'even').' gradeX">';
            foreach ($row as $key => $cell) {
                echo '<td>'.$cell.'</td>';
            }
            echo '</tr>';
        }
        echo '<tbody><table>';
    }
    function set_headers($headers){
        $this->headers = $headers;
    }
    function set_rows($rows) {
        $this->rows = $rows;
    }
    function set_title($title) {
        $this->title = $title;
    }
}
class JsonView {
	public function __construct($data) {
		$this->data = $data;
	}
	public function render() {
		echo json_encode($data);
	}
}