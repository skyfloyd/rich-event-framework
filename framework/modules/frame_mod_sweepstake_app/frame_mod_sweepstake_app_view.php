<?php
class FrameModSweepstakeApp_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModSweepstakeAppManager";


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
			$sweepstakeData = $model->getSweepstakeData();
			
			$frontendRoot = Config::getModulesUrl() . $model->getModuleDirName() . "/frontend/";
						
			$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/" .  $model->getModuleDirName() . ".js'></SCRIPT>
						<link rel='stylesheet' href='" . $frontendRoot . "css/" .  $model->getModuleDirName() . ".css' type='text/css' />
						<link rel='stylesheet' href='" . $frontendRoot . "css/style.css' type='text/css' />
						<link rel='stylesheet' href='" . $frontendRoot . "css/theme-1.css' type='text/css' />
						<link rel='stylesheet' href='" . $frontendRoot . "css/uniform.css' type='text/css' />";
			$script = "
var " . self::$jsManagerName . " = new " . $model->getModuleClassName() . "_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', '" . $model->getSweepstakeId() . "', '" . SweepstakeManager::getSweepstakeAppKey() . "', " . json_encode( $model->getSweepstakeData() ) . ", '" . SweepstakeManager::getEndSweepstakeBaseUrl() . "', '" . $model->getUserComeWayId() . "', " . time() . ", 'frameModSweepstakeApp_headerParent', 'frameModSweepstakeApp_parentContent' );
";
				
			$html = '
	<!-- Top Area -->
	<div class="wraper-top">
		<div class="fixw">
			<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
			<div class="head-block">
				<!-- Heading -->
				<h1 id="frameModSweepstakeApp_headerParent"></h1>
				<!-- /Heading -->
			</div>
		</div>
	</div>
	<!-- /Top Area -->
	
	<!-- Form Area -->
	<div class="wraper-mid">
		<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
		<div class="fixw form-line">
			<div class="form-col-1">
				<div class="form-wrap">
					<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
					<div class="form-inner">
						<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
						<div id="frameModSweepstakeApp_parentContent" style="width: 100%, height: 100%; vertical-align: middle; text-align: left;">
					        <!-- Area to add instructions -->
					    </div>		
						<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
					</div>
				</div>
				<div class="form-bot"></div>
			</div>
			<div class="form-col-2">
				<!-- Form Sidebar Content -->
					&nbsp;
				<!-- /Form Sidebar Content -->
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<!-- /Form Area -->
	
	<!-- Bottom Area -->
	<div class="wraper-bot">
		<div class="clear">&nbsp;</div><!-- avoid collapsing margins -->
		<div class="fixw">
			
		</div>
	</div>
<div style="font-size: 11; padding-right: 20px; padding-top: 20px; text-align: right; font-family: arial; ">Developed by <b>Mher Aghabalyan</b></div>	
	<!-- /Bottom Area -->';

			
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
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__REGISTER_VIEW ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "afterRegisterView", "moduleActionParam" => $model->getSweepstakeUserData() );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == $moduleClass::$STATE__REGISTER_ENTER ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "afterRegisterEnter", "moduleActionParam" => $model->getSweepstakeUserData() );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == $moduleClass::$STATE__REGISTER_EMAIL || $model->getCurrentState() == $moduleClass::$STATE__REGISTER_FACEBOOK_INVITE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "emptyResponse", "moduleActionParam" => "" );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == $moduleClass::$STATE__REGISTER_TWITTER_PUBLISH || $model->getCurrentState() == $moduleClass::$STATE__REGISTER_FACEBOOK_PUBLISH ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "appPoint", "moduleActionParam" => $model->getAddPoint() );
			$res->setJson( $json );
		}
		
		return $res;
	}

}