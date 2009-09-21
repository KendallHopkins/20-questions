<?php

if( ! array_key_exists( "search", $_REQUEST ) ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad input" ) );
}

$sqloo = Common::getSqloo();
$query = $sqloo->newQuery();
$item_ref = $query->table( "item" );

$query->where[] = "$item_ref->name LIKE ".$query->parameter( $_REQUEST["search"]."%" );

$query->column = array(
	"id" => $item_ref->id,
	"name" => $item_ref->name
);

$query->limit = 10;
$query->order[$item_ref->name] = Sqloo::ORDER_ASCENDING;

$query->run();

Common::sendJSON( array( "success" => TRUE, "item_array" => $query->fetchArray() ) );

?>