<?php 

function get_soldiers($constructed_query){
    global $db;
    
    $statement = $db->prepare($constructed_query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    
    return $result;
}//end get_soldiers()

function get_rank_selections () {
    global $db;
    $query = 'SELECT id, rank FROM rank';
    $result = $db->query($query);
    return $result;
}

function get_occupation_selections () {
	global $db;
	$query = 'select id, occupation from occupation order by occupation';
	$result = $db->query($query);
	return $result;
}

function get_location_selections () {
	global $db;
	$query = 'select id, place, state_id from location order by place';
	$result = $db->query($query);
	return $result;
}

function get_state_selections () {
	global $db;
	$query = 'select id, full_name, abbr from state order by full_name';
	$result = $db->query($query);
	return $result;
}

function get_battle_selections () {
	global $db;
	$query = 'select id, battle_name from battle_list order by battle_name';
	$result = $db->query($query);
	return $result;
}

function get_casuality_selections () {
	global $db;
	$query = 'select id, status from casuality_status order by status';
	$result = $db->query($query);
	return $result;
}



?>