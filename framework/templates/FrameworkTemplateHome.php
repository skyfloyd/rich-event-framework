<?php
namespace framework\templates;

use framework\core\FrameworkRequest;
use framework\core\FrameworkResponse;
use framework\core\FrameworkModuleManager;


class FrameworkTemplateHome {
	private $templateId = "framework_template_home";
	
	public function getTemplateId(){
		return $this->templateId;
	}
	
	public function getView( $userRequest ){		
		$userResponse = new FrameworkResponse();
		if( GlobalHelper::isRequestAjax() ){ // json result for ajax call
			$json = array();
			
			$userRequest->setTemplatePlace( "status_bar" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			// if modules are more than one, then view separater is FrameworkModuleManager::$destMod_ModSeparator
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$json[] = $placeResponse->getJson();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			
			$userRequest->setTemplatePlace( "main_content" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			// if modules are more than one, then view separater is FrameworkModuleManager::$destMod_ModSeparator
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$json[] = $placeResponse->getJson();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			
			$userRequest->setTemplatePlace( "menu" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			// if modules are more than one, then view separater is FrameworkModuleManager::$destMod_ModSeparator
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$json[] = $placeResponse->getJson();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			
			
			$userResponse->setStatus( FrameworkResponse::$STATUS__READY );
			$userResponse->setHtml( json_encode( $json ) );
		}else{ //mixed html, script, import response for simple request
			$import = "";
			$script = "";
			$html = "<div align='center' style='background-color: #666666; height: 50px;'>";
					
			$userRequest->setTemplatePlace( "status_bar" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$html .= $placeResponse->getHtml();
				$import .= $placeResponse->getImport();
				$script .= $placeResponse->getScript();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			$html .= "</div><div align='center'><table style='height: 100%; width: 100%;'><tr><td style='width: 200px; vertical-align: top;'>";
			
			$userRequest->setTemplatePlace( "menu" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$html .= $placeResponse->getHtml();
				$import .= $placeResponse->getImport();
				$script .= $placeResponse->getScript();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			$html .= "</td><td style='vertical-align: top;'>";
			
			$userRequest->setTemplatePlace( "main_content" );
			$placeResponse = FrameworkModuleManager::getModuleViewForTemplateArea( (clone $userRequest) );
			if( $placeResponse->getStatus() == FrameworkResponse::$STATUS__READY ){
				$html .= $placeResponse->getHtml();
				$import .= $placeResponse->getImport();
				$script .= $placeResponse->getScript();
			}else{ //ete modulneric inch vor mek@ asuma sax normal chi orinak problem ka kam STATUS__CHANGE_TEMPLATE, miangamic @ndhatum enq u et veradarznum enq verev
				return $placeResponse;
			}
			$html .= "</td></tr></table></div>";
			
			$userResponseView = "<html><head>" . $import . "<script>var _CURRENT_TEMPLATE_ID = '" . self::getTemplateId() . "';</script></head><body style='padding: 0px; margin: 0px;'>" . $html . "<script>" . $script . "</script></body></html>";
			
			$userResponse->setStatus( FrameworkResponse::$STATUS__READY );
			$userResponse->setHtml( $userResponseView );
		}
		
		return $userResponse;
	}
}
?>