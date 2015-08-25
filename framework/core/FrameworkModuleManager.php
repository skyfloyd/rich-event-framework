<?php
namespace framework\core;

use framework\core\FrameworkRequest;
use framework\core\FrameworkResponse;
use easy_db_layer\tables\FrameworkModuleVisibility_table;
use easy_db_layer\tables\FrameworkModule_table;

class FrameworkModuleManager {
	public static $destMod_ModSeparator = "~"; 		//this values are in core.js too
	public static $destMod_ActSeparator = ">"; 		//this values are in core.js too
	public static $respModAct_DataSeparator = "^"; 	//this values are in core.js too //irakanum sranq piti veracven json-i
	
	public static function getModuleViewForTemplateArea( $userRequest ){
		$anyCase = "*";
		$moduleView = "There is no module for template " . $userRequest->getTemplateId() . " -> area " . $userRequest->getTemplatePlace();
		
		$requestActionId = (is_null( $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__actionId ) ) ? $anyCase : $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__actionId ));
		$requestModuleId = (is_null( $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__moduleId ) ) ? $anyCase : $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__moduleId ));
		
		$search = array();
		$search["templateId"] = $userRequest->getTemplateId();
		$search["areaName"] = $userRequest->getTemplatePlace();
		
		$search["request_moduleId"] = $requestModuleId;
		$search["request_actionId"] = $requestActionId;
		//es functiayum hertov stuguma es kokret texin inch modulner en hamapatasxanum sksac konkret request_moduleId u request_actionId arjeqnerov verjacrac request_moduleId u request_actionId cankacac arjeqnerov
		
		$moduleResponse = self::chooseModule( $search, $userRequest );
		if( $moduleResponse->getStatus() == FrameworkResponse::$STATUS__CHANGE_MODULE ){
			$search["request_moduleId"] = $requestModuleId;
			$search["request_actionId"] = $anyCase;
			$moduleResponse = self::chooseModule( $search, $userRequest );
			
			if( $moduleResponse->getStatus() == FrameworkResponse::$STATUS__CHANGE_MODULE ){
				$search["request_moduleId"] = $anyCase;
				$search["request_actionId"] = $requestActionId;
				$moduleResponse = self::chooseModule( $search, $userRequest );
				
				if( $moduleResponse->getStatus() == FrameworkResponse::$STATUS__CHANGE_MODULE ){
					$search["request_moduleId"] = $anyCase;
					$search["request_actionId"] = $anyCase;
					$moduleResponse = self::chooseModule( $search, $userRequest );
				}
			}
		}
		
		return $moduleResponse;
	}
	
	private static function chooseModule( $search, $userRequest ){ // es functian ancnuma konkret es texi vra grancvac u $search-in hamapatasxanox sax modulneri vra hertov, ete inch vor modulic stanumavor inq@ stugi hajordin stuguma hakarak depqum veredarznuma ardyunq@ kanchoxin
		$modulesList = FrameworkModuleVisibility_table::getModuleVisibility( $search );
		//var_dump( $search, $modulesList );
		for( $i = 0; $i < count( $modulesList ); $i++ ){
			$moduleResponse = self::getModuleView( $modulesList[ $i ]["moduleId"], $userRequest );
			if( $moduleResponse->getStatus() != FrameworkResponse::$STATUS__CHANGE_MODULE ){
				return $moduleResponse;
			}
		}
		
		$moduleResponse = new FrameworkResponse();
		$moduleResponse->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		return $moduleResponse;
	}
	
	public static function getModuleView( $moduleId, $userRequest ){
		$destinationModule_ActionIdList = $userRequest->getHttpRequestParam( "destModActIdList" );
		$directAct = "";
		if( $destinationModule_ActionIdList != null && trim( $destinationModule_ActionIdList ) != "" ){
			$directAct = self::getModuleDirectActionName( $moduleId, $destinationModule_ActionIdList );
		}
		
		$moduleInfo = FrameworkModule_table::getModuleInfo( $moduleId );
		$moduleClass = $moduleInfo["class"];
		
		$moduleFile = \Config::getModulesDir() . $moduleInfo["dir_file"] . "/" . $moduleInfo["dir_file"] . ".php";
		include_once( $moduleFile );
		$moduleModelFile = \Config::getModulesDir() . $moduleInfo["dir_file"] . "/" . $moduleInfo["dir_file"] . "_model.php";
		include_once( $moduleModelFile );
		$moduleViewFile = \Config::getModulesDir() . $moduleInfo["dir_file"] . "/" . $moduleInfo["dir_file"] . "_view.php";
		include_once( $moduleViewFile );
		
		$userRequest->setModuleId( $moduleId );
		$mod = new $moduleClass();
		$moduleResponse = $mod->getResponse( (clone $userRequest), $directAct );
		
		return $moduleResponse;
	}
	
	private static function getModuleDirectActionName( $currentModuleId, $destinationModule_ActionIdList ){
		$startIndex = strpos( $destinationModule_ActionIdList, ($currentModuleId . self::$destMod_ActSeparator) );
		if( $startIndex === false ){
			return "";
		}
			
		$endIndex = strpos( $destinationModule_ActionIdList, self::$destMod_ModSeparator, $startIndex );
		if( $endIndex === false ){
			$endIndex = strlen( $destinationModule_ActionIdList ) - 1;
		}
		$leng = $endIndex - $startIndex - strlen( ($currentModuleId . self::$destMod_ActSeparator) ) + 1;
		$act = substr( $destinationModule_ActionIdList, ($startIndex + strlen( ($currentModuleId . self::$destMod_ActSeparator) )) , $leng );
//		var_dump( $act );
		
		return $act;
	}
	
	public static function getDirectActionModuleId( $destinationModule_ActionIdList ){
		if( $destinationModule_ActionIdList == "" || $destinationModule_ActionIdList === null )
			return "";
		
		$endIndex = strpos( $destinationModule_ActionIdList, self::$destMod_ActSeparator );
		if( $endIndex === false )
			return "";
			
		$leng = $endIndex;
		$mod = substr( $destinationModule_ActionIdList, 0, $leng );
		
		return $mod;
	}
}
?>