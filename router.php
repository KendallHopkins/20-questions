<?php

/* AUTOLOAD FUNCTION */
	function __autoload( $classname )
	{
		$autoload_class_file = $_SERVER['DOCUMENT_ROOT']."/model/".str_replace( "_", "/", $classname ).".php";
		if( file_exists( $autoload_class_file ) ) {
			require( $autoload_class_file );
		} else {
			print "FAILED TO INCLUDE FILE: "."/model/".str_replace( "_", "/", $classname ).".php";
			exit();		
		}
	}
	
/* SETUP */
	ini_set( "display_errors", TRUE );									//Always display our errors
	ini_set( "log_errors", TRUE );										//Always log errors to file
	ini_set( "error_log", $_SERVER['DOCUMENT_ROOT']."/error.log" );		//Log to file
	set_error_handler( array( "Error", "errorHandler" ) );				//Setup custom error handler function
	set_exception_handler( array( "Error", "exceptionHandler" ) );		//Setup custom exception handler function
	error_reporting( E_ALL | E_STRICT );								//Helps detect error proned code
	date_default_timezone_set( "America/Detroit" );						//Setup timezone

/* ROUTING */	
	$current_url = "/".trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), "/" );
	$rewrite_file = $_SERVER['DOCUMENT_ROOT']."/controller".$current_url.".php";
	echo $rewrite_file;
	if( file_exists( $rewrite_file ) )
		require( $rewrite_file );
	else
		throw new Exception( "Controller not found for URL $current_url" );

/* EXIT, durrrr */	
	exit();

?>