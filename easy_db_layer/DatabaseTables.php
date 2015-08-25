<?php
namespace easy_db_layer;

interface DatabaseTables {
	public static function getTableName(); 
	public static function getTableFields();
}