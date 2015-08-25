<?php
class FrameModEntriesList_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModEntriesListManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$frontendRoot = Config::getModulesUrl() . "frame_mod_entries_list/frontend/";
					
		$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_entries_list.js'></SCRIPT>
				 	<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_entries_list.css' type='text/css' />
				 	<link rel='stylesheet' href='" . Config::getBaseURL() . "frontend/lib/table_style1/css/style.css' type='text/css' />";
		$script = "
var " . self::$jsManagerName . " = new FrameModEntriesList_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', 'frameModEntriesList_parentElement', 'frameModEntriesList_sweepstakeId' );
";
		$html = "<input type='hidden' id='frameModEntriesList_sweepstakeId' value='" . $model->getSweepstakeId() . "'><table width='80%'><tr><td><div id='frameModEntriesList_parentElement'></div></td></tr></table>";
		
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
			
		if( $model->getCurrentState() == FrameModEntriesList_Model::$STATE__SHOW_LIST ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "refershList", "moduleActionParam" => $model->getListData() );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>