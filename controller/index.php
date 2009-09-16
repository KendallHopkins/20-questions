<?php

$sqloo = Common::getSqloo();
$query = $sqloo->newQuery();
$group_ref = $query->table( "group" );
$query->column = array(
	"id" => $group_ref->id,
	"name" => $group_ref->name,	
);
$query->run();
$group_array = array();
foreach( $query as $row ){
	$group_array[ $row["id"] ] = $row["name"];
}

$smarty = Common::getSmarty();
$smarty->assign( "title" , "20 Questions" );
$smarty->assign( "group_array", $group_array );
$smarty->display( "index.tpl" );

?>