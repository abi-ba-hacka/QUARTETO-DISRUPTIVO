<?php
class Datatable {
	protected $data;
	protected $headers;
	protected $fn_actions;
	public function __construct($data,$headers=null,$fn_actions=null) {
		$this->data = $data;
		$this->headers = $headers;
		$this->fn_actions = $fn_actions;
	}
	public function render() {
		echo '<table width="100%" class="table table-striped table-bordered table-hover">';
		if ($this->headers) {
			echo '<thead><tr>';
			echo '<th>#</th>';
			foreach ($this->headers as $key => $value) {
				echo '<th>'.$value.'</th>';
			}
			if ($this->fn_actions) {
				echo '<th>Acciones</th>'; 
			}
			echo '</tr></thead>';
		}
		if ($this->data) {
			echo '<thead>';
			foreach ($this->data as $index => $row) {
				echo '<tr class="'.($index%2==1?'odd':'even').' gradeX">';
				foreach ($row as $cell) {
					echo '<th>'.$cell.'</th>';
				}
				$fn = $this->fn_actions;
				echo '<td>'.$fn($row).'</td>';
				echo '</tr>';
			}
			echo '</thead>';
		}
		echo '</table>';
	}
}