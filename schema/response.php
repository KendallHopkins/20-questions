<?php

$table = $sqloo->newTable( "response" );
$table->column = array(
	"response" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_BOOLEAN )
	),
	"added" => array(
		Sqloo::COLUMN_DATA_TYPE => array( "type" => Sqloo::DATATYPE_TIME )
	)
);
$table->parent = array(
    "game_id" => array(
		Sqloo::PARENT_TABLE_NAME => "game", 
		Sqloo::PARENT_ALLOW_NULL => FALSE,
		Sqloo::PARENT_DEFAULT_VALUE => NULL,
		Sqloo::PARENT_ON_DELETE => Sqloo::ACTION_CASCADE, 
		Sqloo::PARENT_ON_UPDATE => Sqloo::ACTION_CASCADE
	),
	"question_id" => array(
		Sqloo::PARENT_TABLE_NAME => "question", 
		Sqloo::PARENT_ALLOW_NULL => FALSE,
		Sqloo::PARENT_DEFAULT_VALUE => NULL,
		Sqloo::PARENT_ON_DELETE => Sqloo::ACTION_CASCADE, 
		Sqloo::PARENT_ON_UPDATE => Sqloo::ACTION_CASCADE
	)
);
$table->index = array(
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "response" ),
	),
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "game_id", "question_id" ),
		Sqloo::INDEX_UNIQUE => TRUE
	),
	array(
		Sqloo::INDEX_COLUMN_ARRAY => array( "added" )
	)
);

?>