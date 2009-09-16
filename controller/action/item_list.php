<?php

if( ! array_key_exists( "game_id", $_REQUEST ) ) {
	Common::sendJSON( array( "success" => FALSE, "error" => "Bad input" ) );
}

$responce_average_table_ref = AI::getResponceAverageTable();
$responce_single_table_ref = AI::getResponceSingleTable( (int)$_REQUEST["game_id"] );
$done_count = AI::getDoneResponseCount( $responce_single_table_ref );
$item_probability_table_ref = AI::getItemProbabilityTable( $responce_average_table_ref, $responce_single_table_ref );

$limit = array_key_exists( "limit", $_REQUEST ) ? $_REQUEST["limit"] : 10;
$question_array = AI::getBestQuestionArray( $responce_single_table_ref, $responce_average_table_ref, $item_probability_table_ref, $limit );

Common::sendJSON( array( "success" => FALSE, "question_array" => $question_array ) );

?>