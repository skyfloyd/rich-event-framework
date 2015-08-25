<?php
class Config {
	private static $dbHost = "localhost";
	private static $dbUsername = "root";
	private static $dbPassword = "";
	private static $dbDatabaseName = "sweepstake";
	private static $modulesDir = "framework/modules/";
	private static $templatesDir = "framework/templates/";
	private static $dataFilesDir = "_data_files_/";
	private static $exRootDir = "";
	private static $directCall = true;
	private static $angularJS = true;
	
	private static $baseURL = "//localhost/my_f/";
	
	public static function getDBhost(){
		return self::$dbHost;
	}
	
	public static function getDBuser(){
		return self::$dbUsername;
	}
	
	public static function getDBpass(){
		return self::$dbPassword;
	}
	
	public static function getDBname(){
		return self::$dbDatabaseName;
	}
	
	public static function useAlgularJs(){
		return self::$angularJS;
	}
	
	public static function getBaseURL(){
		return self::$baseURL;
	}
	public static function getBaseDir(){
		return dirname(__FILE__) . "/";
	}
	
	public static function getDataFileDir(){
		return (self::getBaseDir() . self::$dataFilesDir);
	}
	public static function getDataFileUrl(){
		return (self::getBaseURL() . self::$dataFilesDir);
	}
	
	
	public static function setNonDirectCall(){
		self::$directCall = false;
	}
	
	public static function getModulesDir(){
		return (self::getBaseDir() . self::$modulesDir);
	}
	public static function getModulesUrl(){
		return (self::getBaseURL() . self::$modulesDir);
	}
	
	public static function getTemplatesNamespace(){
		return str_replace("/", "\\", self::$templatesDir);
	}
	public static function getTemplatesDir(){
		return (self::getBaseDir() . self::$templatesDir);
	}
	
	
//***************************************************************************************************************************************************************************
}