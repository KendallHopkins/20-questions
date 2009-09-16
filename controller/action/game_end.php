<?php

if( ( ! array_key_exists( "game_id", $_REQUEST ) ) || ( ! array_key_exists( "item_id", $_REQUEST ) ) ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad Input" ) );
}

$sqloo = Common::getSqloo();
$query = $sqloo->newQuery();
$game_ref = $query->table( "game" );
$group_ref = $game_ref->joinParent( "group", "group_id" );
$item_ref = $group_ref->joinChild( "item", "group_id" );

$query->where[] = "$game_ref->id = ".$query->parameter( $_REQUEST["game_id"] );
$query->where[] = "$item_ref->id = ".$query->parameter( $_REQUEST["item_id"] );

$query->column = array( "count" => "COUNT( * )" );

$query->run();
$row = $query->fetchRow();

if( $row["count"] == 0 ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Item is not in game's group" ) );
}

$attribute_array = array(
	"item_id" => (int)$_REQUEST["item_id"]
);

try {
	$sqloo = Common::getSqloo();
	$row_id = $sqloo->update( "game", $attribute_array, "id = ".$_REQUEST["game_id"] );
} catch( Exception $e ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Database error: ".$e->getMessage() ) );
}

Common::sendJSON(
	array(
		"success" => TRUE
	)
);

?>