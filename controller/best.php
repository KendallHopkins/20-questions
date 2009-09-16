<?php

class Temp_Table
{

	private $_table_name = NULL;

	function __construct( $schema, $engine = "MEMORY" )
	{
		static $index = 0;
		$sqloo = Common::getSqloo();		
		$this->_table_name = "temp_".$index++;
		$sqloo->query( "CREATE TEMPORARY TABLE \"{$this->_table_name}\"( {$schema} ) ENGINE={$engine}" );
	}

	function __destruct()
	{
		$sqloo = Common::getSqloo();		
		$sqloo->query( "DROP TEMPORARY TABLE \"{$this->_table_name}\";" );
	}
	
	function __toString()
	{
		return $this->_table_name;
	}

}

function sql_product( $product_column )
{
	return "IF( MIN( $product_column ) = 0, 0, exp(sum(log($product_column))))";
}

$game_id = 1;

$sqloo = Common::getSqloo();

//GET AVERAGES
	$query_array = array();
	
	$query = $sqloo->newQuery();
	$item_ref = $query->table( "item" );
	$game_table_ref = $item_ref->joinChild( "game", "item_id" );
	$response_table_ref = $game_table_ref->joinChild( "response", "game_id" );
	$question_table_ref = $response_table_ref->joinParent( "question", "question_id" );
	
	$query->group = array( $item_ref->id, $question_table_ref->id );
	
	$query->column = array(
		"item_id" => $item_ref->id,
		"question_id" => $question_table_ref->id,
		"average" => "AVG( $response_table_ref->response )"
	);
	$query_array[] = $query;
	
	$query = $sqloo->newQuery();
	$item_ref = $query->table( "item" );
	$question_table_ref = $item_ref->joinCross( "question" );
		
	$query->column = array(
		"item_id" => $item_ref->id,
		"question_id" => $question_table_ref->id,
		"average" => "NULL"
	);
	$query_array[] = $query;
		
	$union_query = $sqloo->union( $query_array );
	$union_query->column["average"] = "IF( ISNULL( MAX( ".$union_query->column["average"]." ) ), 0.5, MAX( ".$union_query->column["average"]." ) )";
	$union_query->group = array( "item_id","question_id" );
	
	$responce_average_table_ref = new Temp_Table( 'item_id int, question_id int, average float' );
	$sqloo->insertQuery( $responce_average_table_ref, $union_query );
	
//GET GAME RESPONCES
	$query_array = array();
	
	$query = $sqloo->newQuery();
	$game_table_ref = $query->table( "game" );
	$response_table_ref = $game_table_ref->joinChild( "response", "game_id" );
	$question_table_ref = $response_table_ref->joinParent( "question", "question_id" );
	
	$query->where[] = "$game_table_ref->id = $game_id";
		
	$query->column = array(
		"question_id" => $question_table_ref->id,
		"response" => "$response_table_ref->response",
		"done" => "1"
	);
	$query_array[] = $query;
	
	$query = $sqloo->newQuery();
	$question_table_ref = $query->table( "question" );
		
	$query->column = array(
		"question_id" => $question_table_ref->id,
		"response" => "NULL",
		"done" => "0"
	);
	$query_array[] = $query;
	
	
	$union_query = $sqloo->union( $query_array );
	$union_query->column["response"] = "IF( ISNULL( MAX( ".$union_query->column["response"]." ) ), 0.5, MAX( ".$union_query->column["response"]." ) )";
	$union_query->group = array( "question_id" );
		
	$responce_single_table_ref = new Temp_Table( 'question_id int, done int, response float' );
	$sqloo->insertQuery( $responce_single_table_ref, $union_query );

//CALCULATE PER ITEM PROBABILITY
	
	$query = $sqloo->newQuery();
	$item_ref = $query->table( "item" );
	$item_responce_average_ref = $item_ref->joinChild( $responce_average_table_ref, "item_id" );
	$question_ref = $item_responce_average_ref->joinParent( "question", "question_id" );
	$item_user_responce = $question_ref->joinChild( $responce_single_table_ref, "question_id" );
	
	$query->group = array( $item_ref->id );
	
	$query->column = array(
		"item_id" => $item_ref->id,
		"probability" => sql_product( "1 - ABS( $item_user_responce->response - $item_responce_average_ref->average )" ),
	);

	$query->run();
	print_r( $query->fetchArray() );

	$item_probability_table_ref = new Temp_Table( 'item_id int, probability float' );
	$sqloo->insertQuery( $item_probability_table_ref, $query );
	
//DO CALCULATION FOR NEXT BEST QUESTION
	$query = $sqloo->newQuery();
	$question_ref = $query->table( "question" );
	$item_user_responce = $question_ref->joinChild( $responce_single_table_ref, "question_id" );
	$query->where[] = "$item_user_responce->done = 0";
	$question_responce_average_ref = $question_ref->joinChild( $responce_average_table_ref, "question_id" );
	$item_ref = $question_responce_average_ref->joinParent( "item", "item_id" );
	$item_probability_ref = $item_ref->joinChild( $item_probability_table_ref, "item_id" );
	
	$query->group = array( $question_ref->id );

	$sort_by_string = "STD( $item_probability_ref->probability * ( $question_responce_average_ref->average - 0.5 ) )";

	$query->column = array(
		"id" => $question_ref->id,
		"std" => $sort_by_string
	);

	$query->order[$sort_by_string] = Sqloo::ORDER_DESCENDING;
	$query->run();
	print_r( $query->fetchArray() );


?>