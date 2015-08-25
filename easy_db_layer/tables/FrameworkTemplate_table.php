<?php
namespace easy_db_layer\tables;

use easy_db_layer\DatabaseTables;
use easy_db_layer\DBconnection;

class FrameworkTemplate_table implements DatabaseTables {
	private static $tableName  = "framework_template";
	private static $fields = array(
								"id" => "templateId",
								"class" => "templateClass",
								"file" => "templateFile"
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
	
	public static function getTemplateInfo( $templateId ){
		$db = new DBconnection();
		$query = "SELECT * FROM " . self::getTableName() . " WHERE " . $db->getWherePart( self::getTableKeyField(), $templateId );
		$result = $db->selectQuery( $query, self::getTableFields() );
		
		return $result[0];
	}
}