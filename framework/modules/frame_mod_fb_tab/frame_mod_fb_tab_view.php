<?php
class FrameModFbTab_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModFbTabManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$res = new FrameworkResponse();
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__START_EDIT ){
			$sweepstakeData = $model->getSweepstakeData();
			
			$frontendRoot = Config::getModulesUrl() . $model->getModuleDirName() . "/frontend/";
						
			$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/" .  $model->getModuleDirName() . ".js'></SCRIPT>
						<link rel='stylesheet' href='" . $frontendRoot . "css/" .  $model->getModuleDirName() . ".css' type='text/css' />";
			$script = "
var " . self::$jsManagerName . " = new " . $model->getModuleClassName() . "_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', '" . $model->getSweepstakeId() . "', '" . SweepstakeManager::getSweepstakeAppKey() . "' );

document.getElementById( 'frameModFbTab_appKey' ).value = '" . $sweepstakeData[ "app_key" ] . "';
document.getElementById( 'frameModFbTab_appSecret' ).value = '" . $sweepstakeData[ "app_secret" ] . "';
document.getElementById( 'frameModFbTab_tabName' ).value = '" . $sweepstakeData[ "share_tab_name" ] . "';
";
			
			
				
			$html = "
<div id='frameModFbTab_parentContent'>
	<div id='frameModFbTab_step1'>
		<div style='color: #8888ff; font-weight: bold; padding: 20px;'>Step 1</div>
		<div style='padding: 2px;'><input type='text' id='frameModFbTab_appKey' placeholder='App Key' style='width: 200px;' /></div>
		<div style='padding: 2px;'><input type='text' id='frameModFbTab_appSecret' placeholder='App Secret' style='width: 200px;' /></div>
		<div style='padding: 8px;'><input type='button' id='frameModFbTab_login' value='login' style='width: 100px;' /></div>
		<div id='frameModFbTab_step1Error'></div>
	</div>
	<div id='frameModFbTab_step2' style='display: none;'>
		<div style='color: #8888ff; font-weight: bold; padding: 20px;'>Step 2</div>
		<div style='padding: 2px;' id='frameModFbTab_pagesListParent'></div>
		<div style='padding: 2px;'><table><tr><td><input type='file' name='imageField' value='Tab Image' id='frameModFbTab_tabImage' /></td><td>(Tab Image: size 111 x 74 px)</td></tr></table></div>
		<div style='padding: 2px;'><input type='text' id='frameModFbTab_tabName' placeholder='Tab Name' style='width: 300px;' /></div>
		<div style='padding: 8px;'><input type='button' id='frameModFbTab_save' value='save' /></div>
		<div id='frameModFbTab_step2Error'></div>
	</div>
</div>
			";
			
			
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$res->setHtml( $html );
			$res->setScript( $script );
			$res->setImport( $include );
		}else{
			$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
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
		
		if( $model->getCurrentState() == $moduleClass::$STATE__SAVE_SUCCESS ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "saveSuccess", "moduleActionParam" => $model->getTabImgUrl() );
			$res->setJson( $json );
		}

		return $res;
	}

}