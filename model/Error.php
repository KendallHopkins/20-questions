<?php

class Error
{
	
	//http://nz.php.net/manual/en/class.errorexception.php
	public static function errorHandler( $error_flag, $error_string, $error_file, $error_line )
	{
		throw new ErrorException( $error_string, 0, $error_flag, $error_file, $error_line );
	}
	
	public static function exceptionHandler( $exception )
	{
		$error_message = "EXCEPTION CODE ".$exception->getCode().": ".$exception->getMessage();
		$stack_trace_array = $exception->getTrace();
		array_unshift( $stack_trace_array,
			array(
				"line" => $exception->getLine(),
				"file" => $exception->getFile()
			)
		);
		$stack_trace = self::_stackTrace( $stack_trace_array );
		self::displayAndLog( $error_message, $stack_trace, TRUE );
	}
	
	private static function displayAndLog( $error_message, $stack_trace, $fatal )
	{
		if( ( ( error_reporting() & E_ALL ) === E_ALL ) && ( (bool)ini_get( "display_errors" ) === TRUE ) ) { print $error_message."<br>\n".$stack_trace."<br>\n<br>\n"; }
		if( ! error_log( $error_message."\n".$stack_trace."\n\n" ) )
			throw new Exception( "######### ERROR NOT LOGGED #########" );
		
		if( $fatal ) exit();
	}
	
	private static function _stackTrace( $stack_trace_data = NULL )
	{
		//if we aren't given a stacktrace, get it from here
		if( is_null( $stack_trace_data ) ) {
			$stack_trace_data = debug_backtrace();
			array_shift( $stack_trace_data ); //remove the call to _stackTrace
		}
		/* Prepare backtrace array */
		$stack_trace_array = array_reverse( $stack_trace_data );
		//pull off last 2 items, since they are internal
		//array_pop( $stack_trace_array );
		
		/* Create backtrace string */
		$strack_trace_string_array = "";
		foreach( $stack_trace_array as $stack_trace ) {
			if( array_key_exists( "file", $stack_trace ) && array_key_exists( "line", $stack_trace ) ) {
				$strack_trace_string_array[] = sprintf( 
					"%s( %s line %s )",
					Common::safeArrayAccess( "function", $stack_trace, "" ),
					str_replace( LOCAL_DOCUMENT_ROOT, "", $stack_trace["file"] ),
					(string)$stack_trace["line"]
				);
			} else {
				$strack_trace_string_array[] = sprintf( 
					"%s( internal )",
					$stack_trace["function"]
				);
			}
		}
		return implode( " -> ", $strack_trace_string_array );
	}
	
}

?>