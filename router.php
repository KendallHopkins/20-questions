<?php

/* SET CODE FOLDER */
    define("LOCAL_DOCUMENT_ROOT", dirname( __FILE__ ) );
    
/* AUTOLOAD FUNCTION */	
	function __autoload( $classname )
	{
		$autoload_class_file = LOCAL_DOCUMENT_ROOT."/model/".str_replace( "_", "/", $classname ).".php";
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
	ini_set( "error_log", LOCAL_DOCUMENT_ROOT."/error.log" );			//Log to file
	set_error_handler( array( "Error", "errorHandler" ) );				//Setup custom error handler function
	set_exception_handler( array( "Error", "exceptionHandler" ) );		//Setup custom exception handler function
	error_reporting( E_ALL | E_STRICT );								//Helps detect error proned code
	date_default_timezone_set( "America/Detroit" );						//Setup timezone

/* ROUTING */
	$url_prefix_length = strlen( LOCAL_DOCUMENT_ROOT ) - strlen( $_SERVER["DOCUMENT_ROOT"] );
	$url_path = substr( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), $url_prefix_length );
	$url_path = ltrim( $url_path, "/" );
	$slug_array = explode( "/", $url_path );
	
	//empty slugs become index
	$slug_array = preg_replace( "/^$/", "index", $slug_array ); 
		
	//remove all slugs that begin with "." for safety
	function filter_dots( $slug ) { return substr( $slug, 0, 1 ) !== "."; }
	$slug_array = array_filter( $slug_array, "filter_dots" );
	
	//create relative path to controller
	$relative_controller_path =
		"/controller/".
		implode( "/", $slug_array ).
		".php";
	
	//create absolute path to controller
	$absolute_controller_path = LOCAL_DOCUMENT_ROOT.$relative_controller_path;
	if( ! file_exists( $absolute_controller_path ) )
		throw new Exception( "File didn't exists at path: $relative_controller_path" );
	
	require( $absolute_controller_path );
	
	exit();

?>