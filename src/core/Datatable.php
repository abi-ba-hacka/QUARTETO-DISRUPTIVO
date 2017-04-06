<?php
class Datatable {
	protected $data;
	protected $columns;
	protected $fn_actions;
	public function __construct($data,$columns=null,$fn_actions=null) {
		$this->data = $data;
		$this->columns = $columns;
		$this->fn_actions = $fn_actions;
	}
	public function render() {
		echo '<table width="100%" class="table table-striped table-bordered table-hover">';
		if ($this->columns) {
			echo '<thead><tr>';
			foreach ($this->columns as $column) {
				echo '<th>'.$column.'</th>';
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
				foreach ($this->columns as $column) {
					echo '<th>'.$row[$column].'</th>';
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