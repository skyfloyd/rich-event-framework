<?php
namespace easy_db_layer\tables;

use easy_db_layer\DatabaseTables;
use easy_db_layer\DBconnection;

class FrameworkUser_table implements DatabaseTables {
	private static $tableName  = "framework_user";
	private static $fields = array(
								"id" => "id",
								"login" => "login",
								"pass" => "pass",
								"full_name" => "full_name",
								"role" => "role",
								"create_date" => "create_date",
								"last_login_date" => "last_login_date"
							 );
	
	public static function getTableName(){
		return self::$tableName;
	}
	
	public static function getTableFields(){
		return self::$fields;
	}
	
	private static function getTableKeyField(){
		return self::$fields["id"];
	}
	
	public static function login( $login, $pass ){
		$values = array();
		$values[ "login" ] = $login;
		$values[ "pass" ] = $pass;
		
		$db = new DBconnection();
		$query = "SELECT * FROM " . self::getTableName() . " WHERE " . $db->getWherePart( self::getTableFields(), $values );
		$result = $db->selectQuery( $query, self::getTableFields() );
		
		if( isset( $result[0] ) ){
			unset( $result[0][ "pass" ] );
			return $result[0];
		}
		return null;

	}
}