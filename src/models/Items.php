<?php 
class Items extends PdoDAO{
	$object = new stdClass(false);
    $object->id = $row['id']*1;
    $object->description = $row['description'];
        $object->traffic_class = $row['trafficClass'];        	

	return $object;
}
