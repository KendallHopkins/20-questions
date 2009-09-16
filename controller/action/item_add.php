<?php

if( ( ! array_key_exists( "name", $_REQUEST ) ) || ( ! array_key_exists( "group_id", $_REQUEST ) ) ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad input" ) );
}

$attribute_array = array(
	"name" => (string)$_REQUEST["name"],
	"group_id" => (int)$_REQUEST["group_id"]
);

try {
	$sqloo = Common::getSqloo();
	$row_id = $sqloo->insert( "item", $attribute_array );
} catch( Exception $e ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Database error while inserting item: ".$e->getMessage() ) );
}

Common::sendJSON(
	array(
		"success" => TRUE,
		"item_id" => $row_id
	)
);

?>