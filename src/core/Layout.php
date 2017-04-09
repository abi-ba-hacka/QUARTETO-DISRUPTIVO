<?php
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

// 		                        Cerveza Patagonia introwww.youtube.comhttps://youtu.be/niwRiJk5aY0                        
// https://youtu.be/yxMDnQBJRr8                        
// https://youtu.be/_yATb23AilE                        
// https://youtu.be/lQRKL1A6KGo                        
// https://youtu.be/CWOKNn9bgE0                        
		
?>
		  
<iframe style="width: 100%;height: 100%;position:absolute;" width="854" height="480" src="https://www.youtube.com/embed/aFLHKdR9fsQ?rel=0&autoplay=1&controls=0&enablejsapi=1&loop=1&iv_load_policy=1&version=3" frameborder="0" allowfullscreen></iframe>

<div id="form" style="position:absolute;bottom: 5px;text-align: center;width: 100%; height: 100px;margin-top: 20px">
	
	<div style="background: rgba(0,0,0,0.8);height: 70px;width: 30%;margin:auto;color: white;padding: 10px">
		
<form method="post" action="save">
	<label>Ingresá tu <b>email</b> para revivir la experiencia</label> <br> <input type="email" autofocus="true" name="email" style="color: black;">
	<input type="submit" name="" value="Ingresar" style="color:black;font-weight: bold">
</form>


	</div>
</div>
    <?php

		//echo '<nav class="navbar navbar-default navbar-static-top" role="navigation">';
		//echo '<ul class="nav navbar-top-links navbar-right">';
		//echo '<li><a href="'.App::url('clientes').'">Clientes</a></li>';
		//echo '<li><a href="'.App::url('facturas').'">Facturas</a></li>';
		//echo '<li><a href="'.App::url('Items').'">items</a></li>';
		//echo '</ul>';
		//echo '</nav>';
		//App::$flash_message->display();


		echo '<div id="page-wrapper">';
	    echo '<div class="panel-body">';

	    if (isset(App::$response['view'])) {
			require_once 'src/view/'.App::$response['view'].'.php';
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