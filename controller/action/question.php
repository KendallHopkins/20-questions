<?php

if( ( ! array_key_exists( "game_id", $_REQUEST ) ) ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad input" ) );
}

if( array_key_exists( "question_id", $_REQUEST ) &&
	array_key_exists( "response", $_REQUEST )
) {
	$attribute_array = array(
		"game_id" => (int)$_REQUEST["game_id"],
		"question_id" => (int)$_REQUEST["question_id"],
		"response" => (bool)$_REQUEST["response"]
	);
	
	try {
		$sqloo = Common::getSqloo();
		$row_id = $sqloo->insert( "response", $attribute_array );
	} catch( Exception $e ) {
		Common::sendJSON( array( "success" => FALSE, "error" => "Database error while inserting Question: ".$e->getMessage() ) );
	}
}

$responce_average_table_ref = AI::getResponceAverageTable();
$responce_single_table_ref = AI::getResponceSingleTable( (int)$_REQUEST["game_id"] );
$done_count = AI::getDoneResponseCount( $responce_single_table_ref );
$item_probability_table_ref = AI::getItemProbabilityTable( $responce_average_table_ref, $responce_single_table_ref );
if( $done_count < 20 ) {
	$question_info = AI::getBestQuestionInfo( $responce_single_table_ref, $responce_average_table_ref, $item_probability_table_ref );
	Common::sendJSON(
		array(
			"success" => TRUE,
			"type" => "normal",
			"question" => $question_info,
			"count" => $done_count
		)
	);
} else {
	$answer_info = AI::getBestAnswerItem( $item_probability_table_ref );
	Common::sendJSON(
		array(
			"success" => TRUE,
			"type" => "final",
			"answer" => $answer_info,
			"count" => $done_count
		)
	);
}



?>