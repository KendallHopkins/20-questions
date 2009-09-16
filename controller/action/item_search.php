<?php

if( ( ! array_key_exists( "game_id", $_REQUEST ) ) ||
	( ! array_key_exists( "search", $_REQUEST ) )
) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad input" ) );
}

$sqloo = Common::getSqloo();
$query = $sqloo->newQuery();
$game_ref = $query->table( "game" );
$group_ref = $game_ref->joinParent( "group", "group_id" );
$item_ref = $group_ref->joinChild( "item", "group_id" );

$query->where[] = "$game_ref->id = ".$query->parameter( $_REQUEST["game_id"] );
$query->where[] = "$item_ref->name LIKE ".$query->parameter( $_REQUEST["search"]."%" );

$query->column = array(
	"id" => $item_ref->id,
	"name" => $item_ref->name
);

$query->limit = 10;

$query->run();

Common::sendJSON( array( "success" => TRUE, "item_array" => $query->fetchArray() ) );

?>