<?php

$json_array = array();

if( ! array_key_exists( "group_id", $_REQUEST ) ) {
	Common::sendJSON(
		array(
			"success" => FALSE,
			"error" => "Didn't set group_id"
		)
	);
}

$sqloo = Common::getSqloo();
$attribute_array = array(
	"group_id" => (int)$_REQUEST["group_id"]
);

try {
	$row_id = $sqloo->insert( "game", $attribute_array );
	$json_array["success"] = TRUE;
	$json_array["game_id"] = $row_id;
} catch( Exception $e ) {
	Common::sendJSON(
		array(
			"success" => FALSE,
			"error" => "Database Error: ".$e->getMessage()
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