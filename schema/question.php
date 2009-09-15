<?php

$table = $sqloo->newTable( "cmc_brand" );
$table->column = array(
	"question" => array(
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