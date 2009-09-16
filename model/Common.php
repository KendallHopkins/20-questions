<?php

class Common
{

	static public function getUrlArray()
    {
    	//simple function to return array of url items (ie /home/item ~> array( "home", "item" )
    	static $url_array = NULL;
    	if( is_null( $url_array ) ) {
			$url_array = explode( '/', ltrim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) );
			foreach( $url_array as &$url_part ) {
				$url_part = ( $url_part !== "" ) ? strtolower( $url_part ) : "index";
			}
		}
		return $url_array;
    }
    
    static public function safeArrayAccess( $key, array $array, $fallback_value = NULL )
	{
		return array_key_exists( $key, $array ) ? $array[$key] : $fallback_value;
	}
	
	static function sqlProduct( $product_column )
	{
		return "IF( MIN( $product_column ) = 0, 0, EXP( SUM( LOG( $product_column ) ) ) )";
	}
	
	static function sendJSON( $json_data )
	{
		print json_encode( $json_data ); exit();
	}
	
	/* wrapper for Sqloo */

	static public function master_pool()
	{
		require( $_SERVER['DOCUMENT_ROOT']."/configure/db.php" );
		return $master_pool[ array_rand( $master_pool ) ];
	}

	//simple load table function
	static public function load_table( $table_name, $sqloo )
	{
		if( ! file_exists( $_SERVER['DOCUMENT_ROOT']."/schema/".$table_name.".php" ) ) throw new Exception( "failed to loaded" );
		require( $_SERVER['DOCUMENT_ROOT']."/schema/".$table_name.".php" );
	}

	static public function filter_table_folder( $table_name )
	{
		return substr( $table_name, 0, 1 ) !== ".";
	}

	//simple list table function
	static public function list_all_tables()
	{
		$file_array = array_filter( scandir( $_SERVER['DOCUMENT_ROOT']."/schema/" ), "Common::filter_table_folder" );
		$table_array = array();
		foreach( $file_array as $file_name ) {
			$table_array[] = substr( $file_name, 0, -4 ); //remove .php
		}
		return $table_array;
	}

	//We init Sqloo with functions to get database configuration and load tables dynamically
	static public function getSqloo()
	{
		static $sqloo = NULL;
		if( is_null( $sqloo ) )
			$sqloo = new Sqloo( "Common::master_pool", NULL, "Common::load_table", "Common::list_all_tables" );
		return $sqloo;
	}
	
	/* wrapper for smarty */
	
	static public function getSmarty()
	{
		$smarty = new Smarty();

		$smarty->template_dir = $_SERVER['DOCUMENT_ROOT']."/view/";
		$smarty->compile_dir  = $_SERVER['DOCUMENT_ROOT']."/view_c/";
		//$smarty->config_dir   = '/web/www.example.com/guestbook/configs/';
		return $smarty;
	}

}

?>