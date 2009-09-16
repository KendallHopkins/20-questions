<?php

$table = $sqloo->newTable( "group" );
$table->column = array(
	"name" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_STRING, "size" => 128 )
	),
	"added" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	),
	"modified" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	)
);
$table->parent = array(
);
$table->index = array(
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "name" ),
		Sqloo::INDEX_UNIQUE => TRUE
	),
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "added" )
	),
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "modified" )
	)
);

?>