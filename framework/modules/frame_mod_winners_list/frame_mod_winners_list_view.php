<?php
class FrameModWinnersList_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModWinnersListManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	

	private static function getHTMLResponse( $model ){
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__MAIN_VIEW ){
			$sweepstakeData = $model->getListData();
			
			$frontendRoot = Config::getModulesUrl() . $model->getModuleDirName() . "/frontend/";
						
			$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/" .  $model->getModuleDirName() . ".js'></SCRIPT>
						<link rel='stylesheet' href='" . $frontendRoot . "css/" .  $model->getModuleDirName() . ".css' type='text/css' />
						<link rel='stylesheet' href='" . Config::getBaseURL() . "frontend/lib/table_style1/css/style.css' type='text/css' />";
			$script = "
var " . self::$jsManagerName . " = new " . $model->getModuleClassName() . "_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', '" . $model->getSweepstakeId() . "', 'frameModWinnersList_parentElement', 'frameModWinnersList_chooseWinners', 'frameModWinnersList_count' );
";
			
			$chooseWinners = "
<table align='right'><tr>
	<td><input type='number' id='frameModWinnersList_count' style='width: 50px; height: 50px;' placeholder='count' value='1' /></td>
	<td><input type='button' id='frameModWinnersList_chooseWinners' value='Choose Winners' style='height: 50px;' /></td>
</tr></table>";
		
			$html = "
<table width='80%'>
<tr><td>" . $chooseWinners . "</td></tr>
<tr><td><div id='frameModWinnersList_parentElement'></div></td></tr>
</table>";
			
			
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$res->setHtml( $html );
			$res->setScript( $script );
			$res->setImport( $include );
		}
		
		return $res;
	}
	
	private static function getAjaxResponse( $model ){
		$parentResponse = parent::getBasicAjaxResponse( $model );
		if( !is_null( $parentResponse ) ){
			return $parentResponse;
		}
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__SHOW_LIST ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "winnersList", "moduleActionParam" => $model->getListData() );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == $moduleClass::$STATE__EMAIL_SENT ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "emailSent", "moduleActionParam" => "" );
			$res->setJson( $json );
		}

		return $res;
	}
}
?>