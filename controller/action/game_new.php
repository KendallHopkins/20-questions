<?php

if( ! array_key_exists( "group_id", $_REQUEST ) ) {
	Common::sendJSON(
		array(
			"success" => FALSE,
			"error" => "Didn't set group_id"
		)
	);
}

$attribute_array = array(
	"group_id" => (int)$_REQUEST["group_id"]
);

try {
	$sqloo = Common::getSqloo();
	$row_id = $sqloo->insert( "game", $attribute_array );
} catch( Exception $e ) {
	Common::sendJSON(
		array(
			"success" => FALSE,
			"error" => "Database error: ".$e->getMessage()
		)
	);
}

Common::sendJSON(
	array(
		"success" => TRUE,
		"game_id" => $row_id
	)
);

?>