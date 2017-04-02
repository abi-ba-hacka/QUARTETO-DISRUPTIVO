<?php
class HtmlNode {
	public $tag = 'div';
	public $attr = array();
	public $content = array();
	public function render() {
		echo '<'.$this->tag;
		foreach ($this->attr as $key => $value) {
			echo ' '.$key.'="'.$value.'" ';
		}
		echo '>';
		foreach ($this->content as $key => $element) {
			$element->render();
		}
		echo '</'.$this->tag.'>';
	}
	public function __construct($tag = 'div', $attr = array(), $content = array()) {
		$this->tag = $tag;
		$this->attr = $attr;
		$this->content = $content;
	}
	public function __get($name) {
		$this->$name = array();
	}
	public function find_or_create() {
		$this->find();
	}
	public function find($tag=null, $id=null, $class=array(), $attr = array()) {
		//todo check if not null and array
		//TODO validate input selectors	
		//find one or many
		//create if not exists
		//
		$matching_node_index = null;
		for ($i = 0; $i < count($this->content); $i++) {
			$match = true; $node = $this->content[$i];
			if ($tag != null) {
				if ($tag != $node->tag) {
					$match = false;
				}
			}
			if ($match AND $id != null) {
				if ($id != $node->attr['id']) {
					$match = false;
				} else {
					$all_class_in_attr = true;
					if (count($class) > count($node->attr['class'])) {
						$all_class_in_attr = false;
					} else {
						for($ii=0; $ii < count($class); $ii++) {
							if (!in_array($current_class_param, $node->attr['class'])) {
								$all_class_in_attr = false;
								break;
							}
						}
					}
					if (!$all_class_in_attr) {
						$match = false;
					}
				}
			}
			if ($match) {
				$matching_node_index = $i;
				break;
				//TODO what hapend if are there more than one matching node?
			}
		}
		if ($matching_node_index===null) {
			$this->content[] = new HtmlNode($tag, $attr = array('id' => $id, 'class' => $class));
			$matching_node_index = count($this->content)-1; 
		}
		return $this->content[$matching_node_index];
	}
}