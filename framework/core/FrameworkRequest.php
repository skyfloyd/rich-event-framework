<?php
namespace framework\core;

class FrameworkRequest {
	
	private $httpRequest = null;
	private $templateId = null;
	private $templatePlace = null;
	private $moduleId = null;
	
	public static $REQUEST_PARAM__templateId = "templateId";
	public static $REQUEST_PARAM__ajaxCall = "ajaxCall";
	public static $REQUEST_PARAM__actionId = "actionId";
	public static $REQUEST_PARAM__moduleId = "moduleId";
	
	public function setHttpRequest( $httpRequest ){
		$this->httpRequest = $httpRequest;
	}
	public function getHttpRequest(){
		return $this->httpRequest;
	}
	public function getHttpRequestParam( $param ){
		return (isset( $this->httpRequest[ $param ] ) ? $this->httpRequest[ $param ] : null);
	}
	public function getHttpRequestParamsStr( $excludeParams ){
		$vars = $this->getHttpRequest();
		$str = "";
		foreach( $vars as $name => $value ){
			$find = false;
			foreach( $excludeParams as $ex ){
				if( $ex == $name ){
					$find = true;
				}
			}
			
			if( !$find ){
				$str .= "&" . $name . "=" . $value;
			}
		}
		$str = substr( $str, 1 );
		
		return $str;
	}
	public function changeHttpRequestParam( $newKeyVal ){
		foreach( $newKeyVal as $name => $value ){
			$this->httpRequest[ $name ] = $value;
		}
	}
	
	public function setTemplateId( $templateId ){
		$this->templateId = $templateId;
	}
	public function getTemplateId(){
		return $this->templateId;
	}
	
	public function setTemplatePlace( $templatePlace ){
		$this->templatePlace = $templatePlace;
	}
	public function getTemplatePlace(){
		return $this->templatePlace;
	}
	
	public function setModuleId( $moduleId ){
		$this->moduleId = $moduleId;
	}
	public function getModuleId(){
		return $this->moduleId;
	}
}