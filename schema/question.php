<?php

$table = $sqloo->newTable( "question" );
$table->column = array(
	"name" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_STRING, "size" => 1024 )
	),
	"added" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	),
	"modified" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	)
);
$table->parent = array(
	"group_id" => array(
		Sqloo::PARENT_TABLE_NAME => "group", 
		Sqloo::PARENT_ALLOW_NULL => FALSE,
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