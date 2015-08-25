<?php
class FrameModSweepstakeOverview_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModSweepstakeOverviewManager";


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
		
		if( $model->getCurrentState() == $moduleClass::$STATE__START ){
			$frontendRoot = Config::getModulesUrl() . $model->getModuleDirName() . "/frontend/";
						
			$include = "
<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/" .  $model->getModuleDirName() . ".js'></SCRIPT>
<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/highcharts.js'></SCRIPT>
<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/modules/exporting.js'></SCRIPT>
<link rel='stylesheet' href='" . $frontendRoot . "css/" .  $model->getModuleDirName() . ".css' type='text/css' />";

			$script = "
var " . self::$jsManagerName . " = new " . $model->getModuleClassName() . "_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', '" . $model->getSweepstakeId() . "', JSON.parse( '" . json_encode( $model->getSweepstakeStatistics()  ) . "' ), " . ($model->isSweepstakeActive() ? "true" : "false") . ", '" . $model->getModuleClassName() . "_activate', '" . $model->getModuleClassName() . "_statisticsContainer', '" . $model->getModuleClassName() . "_statistcsSumContainer' );
";
			
			$html = "
<div style='padding-top: 20px; padding-bottom: 20px; text-align: center;'><input type='button' value='' id='" . $model->getModuleClassName() . "_activate' style='width: 200px; height: 50px;'></div>
<div style='padding-top: 20px; padding-bottom: 20px; text-align: center;' id='" . $model->getModuleClassName() . "_statistcsSumContainer'></div>
<div id='" . $model->getModuleClassName() . "_statisticsContainer'></div>

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
		
		if( $model->getCurrentState() == $moduleClass::$STATE__ACTIVE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "activeSweepstake", "moduleActionParam" => "" );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == $moduleClass::$STATE__PASSIVE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "passiveSweepstake", "moduleActionParam" => "" );
			$res->setJson( $json );
		}

		return $res;
	}

}