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
		Common::sendJSON( array( "success" => FALSE, "error" => "Database error while inserting question: ".$e->getMessage() ) );
	}
}

$responce_average_table_ref = AI::getResponceAverageTable();
$responce_single_table_ref = AI::getResponceSingleTable( (int)$_REQUEST["game_id"] );
$done_count = AI::getDoneResponseCount( $responce_single_table_ref );
$item_probability_table_ref = AI::getItemProbabilityTable( $responce_average_table_ref, $responce_single_table_ref );
if( $done_count < 20 ) {
	$question_array = AI::getBestQuestionArray( $responce_single_table_ref, $responce_average_table_ref, $item_probability_table_ref, 1 );
	$next_question = Common::safeArrayAccess( 0, $question_array );
	if( ! is_null( $next_question ) ) {
		Common::sendJSON(
			array(
				"success" => TRUE,
				"type" => "normal",
				"question" => $next_question,
				"count" => $done_count
			)
		);
	} else {
		Common::sendJSON( array( "success" => FALSE, "error" => "We don't have any more questions." ) );
	}
	
} else {
	$answer_info = AI::getBestAnswerItem( $item_probability_table_ref, 1 );
	$best_answer = Common::safeArrayAccess( 0, $answer_info );

	if( ! is_null( $best_answer ) ) {
		Common::sendJSON(
			array(
				"success" => TRUE,
				"type" => "final",
				"answer" => $best_answer,
				"count" => $done_count
			)
		);
	} else {
		Common::sendJSON( array( "success" => FALSE, "error" => "We don't have any items." ) );
	}
}



?>