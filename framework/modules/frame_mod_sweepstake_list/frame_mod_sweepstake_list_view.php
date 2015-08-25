<?php
class FrameModSweepstakeList_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModSweepstakeListManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$frontendRoot = Config::getModulesUrl() . "frame_mod_sweepstake_list/frontend/";
					
		$include = "
				<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_sweepstake_list.css' type='text/css' />
				<link rel='stylesheet' href='" . Config::getBaseURL() . "frontend/lib/table_style1/css/style.css' type='text/css' />";
		$script = "";
		$html = "
<div ng-app='FrameModSweepstakeList_Module' data-ng-init=\"_MODULE_ID_='" . $model->getModuleId() . "';sweepstakeBaseUrl='" . SweepstakeManager::getEndSweepstakeBaseUrl() . "'\"  ng-controller=\"FrameModSweepstakeList_Controller\">
<div ng-include=\"'" . $frontendRoot . "html/frame_mod_sweepstake_list.html'\"></div>
</div>

<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_sweepstake_list.js'></SCRIPT>";
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__READY );
		$res->setHtml( $html );
		$res->setScript( $script );
		$res->setImport( $include );
		
		return $res;
	}
	
	private static function getAjaxResponse( $model ){
		$parentResponse = parent::getBasicAjaxResponse( $model );
		if( !is_null( $parentResponse ) ){
			return $parentResponse;
		}
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
			
		if( $model->getCurrentState() == FrameModSweepstakeList_Model::$STATE__SHOW_LIST ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleId" => $model->getModuleId(), "moduleAction" => "refershList", "moduleActionParam" => $model->getListData() );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == FrameModSweepstakeList_Model::$STATE__SHOW_ACTIVITY ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleId" => $model->getModuleId(), "moduleAction" => "drawSweepstakeActivity", "moduleActionParam" => array("id" => $model->getSweepstakeId(), "data" => $model->getSweepstakeActivity()) );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>