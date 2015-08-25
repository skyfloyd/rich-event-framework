<?php
use framework\core\module\FrameModViewParent;
use lib\GlobalHelper;
use framework\core\FrameworkRequest;
use framework\core\FrameworkResponse;

class FrameModUserLoginRegister_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModUserLoginRegisterManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$frontendRoot = Config::getModulesUrl() . "frame_mod_user_login_register/frontend/";
					
		$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_user_login_register.js'></SCRIPT>
				 	<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_user_login_register.css' type='text/css' />";
		$script = "
var " . self::$jsManagerName . " = new FrameModUserLoginRegister_manager();
" . self::$jsManagerName . ".startWork( 'frameModUserLoginRegister_parentElement' );
";
		$html = "<div id='frameModUserLoginRegister_parentElement'></div>";
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__READY );
		$res->setHtml( $html );
		$res->setScript( $script );
		$res->setImport( $include );
		
		return $res;
	}
	
	private static function getAjaxResponse( $model ){
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__READY );
			
		if( $model->getCurrentState() == FrameModUserLoginRegister_Model::$STATE__NO_USER ){
			$json = array( "moduleAction" => "defaultView" );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == FrameModUserLoginRegister_Model::$STATE__ADMIN_LOGIN ){
			$json = array( "moduleAction" => "showLoginData", "moduleActionParam" => $model->getLoginUserData() );
			$res->setJson( $json );
		}else
		if( $model->getCurrentState() == FrameModUserLoginRegister_Model::$STATE__INCORRECT_LOGIN ){
			$json = array( "moduleAction" => "showIncorrectLogin", "moduleActionParam" => "Your username or password is incorrect" );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>