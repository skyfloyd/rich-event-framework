<?php
namespace easy_db_layer\tables;

use easy_db_layer\DatabaseTables;
use easy_db_layer\DBconnection;

class FrameworkModuleVisibility_table implements DatabaseTables {
	private static $tableName  = "framework_module_visibility";
	private static $fields = array(
								"id" => "id",
								"templateId" => "templateId",
								"areaName" => "areaName",
								"request_moduleId" => "request_moduleId",
								"request_actionId" => "request_actionId",
								"moduleId" => "moduleId",
								"requestOrder" => "requestOrder"
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
	
	/**
	 * return module id by search parameters
	 *
	 * @param Array $values
	 * @return data array
	 */
	public static function getModuleVisibility( $values ){
		$db = new DBconnection();
		$query = "SELECT * FROM " . self::getTableName();
		
		$where = $db->getWherePart( self::getTableFields(), $values );
		if( $where != "" ){
			$query .= " WHERE $where";
		}
		$query .= " ORDER BY `" . self::$fields[ "requestOrder" ] . "`";
				
		$result = $db->selectQuery( $query, self::getTableFields() );
		
		
		return $result;
	}
}