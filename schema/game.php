<?php

$table = $sqloo->newTable( "game" );
$table->column = array(
	"added" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	),
	"modified" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	)
);
$table->parent = array(
	"item" => array(
		Sqloo::PARENT_TABLE_NAME => "item", 
		Sqloo::PARENT_ALLOW_NULL => TRUE,
		Sqloo::PARENT_DEFAULT_VALUE => NULL,
		Sqloo::PARENT_ON_DELETE => Sqloo::ACTION_CASCADE, 
		Sqloo::PARENT_ON_UPDATE => Sqloo::ACTION_CASCADE
	)
);
$table->index = array(
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "added" )
	),
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "modified" )
	)
);

?>