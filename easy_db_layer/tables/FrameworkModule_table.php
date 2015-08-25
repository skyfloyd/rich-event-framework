<?php
namespace easy_db_layer\tables;

use easy_db_layer\DatabaseTables;
use easy_db_layer\DBconnection;

class FrameworkModule_table implements DatabaseTables {
	private static $tableName  = "framework_module";
	private static $fields = array(
								"id" => "moduleId",
								"class" => "className",
								"dir_file" => "dir_fileName"
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
	
	public static function getModuleInfo( $moduleId ){
		$db = new DBconnection();
		$query = "SELECT * FROM " . self::getTableName() . " WHERE " . $db->getWherePart( self::getTableKeyField(), $moduleId );
		$result = $db->selectQuery( $query, self::getTableFields() );
		
		return $result[0];
	}
}