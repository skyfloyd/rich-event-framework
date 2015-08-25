<?php
namespace framework;

use framework\core\FrameworkRequest;
use framework\core\FrameworkResponse;
use easy_db_layer\tables\FrameworkTemplate_table;

class FrameworkController {
	private static $defaultTemplateId = "framework_template_default";
	
	public static function requestFromUser(){
		$userRequest = new FrameworkRequest();
		$userRequest->setHttpRequest( $_REQUEST );
		
		$userResponse = new FrameworkResponse();
		$userResponse->setStatus( FrameworkResponse::$STATUS__CHANGE_TEMPLATE );
		$userResponse->setData( $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__templateId ) );
		
		$returnView = "";
		$limit = 20;
		$count = 0;
		while( $userResponse->getStatus() == FrameworkResponse::$STATUS__CHANGE_TEMPLATE ){
			$count++;
			$oldTemplateId = $userResponse->getData();
			$userResponse = self::getTemplateResponse( $userResponse->getData(), $userRequest );
			
			//if( $userResponse->getStatus() == FrameworkResponse::$STATUS__CHANGE_TEMPLATE && $userResponse->getData() == $oldTemplateId ){
			if( $count > $limit ){
				throw new \Exception( 'My Framework Exeption: template id forever in cycle problem' );
			} 
		}

		return $userResponse->getHtml();
	}
	
	private static function getTemplateResponse( $templateId, $userRequest ){
		if( is_null( $templateId ) || trim( $templateId ) == "" ){
			$templateId = self::$defaultTemplateId;
		}
		
		$templateInfo = FrameworkTemplate_table::getTemplateInfo( $templateId );
		$templateClass = $templateInfo["class"];
		$templateFile = $templateInfo["file"];
				
		$className = \Config::getTemplatesNamespace() . $templateClass;
		$templ = new $className();
		
		$userRequest->setTemplateId( $templateId );
		$userResponse = $templ->getView( (clone $userRequest) );
		return $userResponse;
	}
}