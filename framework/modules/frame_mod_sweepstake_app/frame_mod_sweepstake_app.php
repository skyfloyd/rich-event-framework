<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";
require_once Config::getBaseDir() . "database_layer/tables/fb_user_table.php";
require_once Config::getBaseDir() . "database_layer/tables/user_publish_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_statistics_table.php";

require_once Config::getBaseDir() . "lib/sweepstake_manager.php";


class FrameModSweepstakeApp extends FrameModParent {
	private static $modName = "Sweepstake App";
	private static $modId = "SweepstakeApp";
	protected static function getModuleId(){
		return self::$modId;
	}
	public static function getName(){
		return self::$modName;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
		//0 => array( "templateId"=>"*", "moduleState"=>"finish_creation_save", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" )
	);
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener__startApp( $userRequest ){
		if( !GlobalHelper::isRequestAjax() ){
			$this->model->startApp( $userRequest->getHttpRequestParam( "sweepstakeId" ), (is_null( $userRequest->getHttpRequestParam( "wi" ) ) ? $userRequest->getHttpRequestParam( "request_ids" ) : $userRequest->getHttpRequestParam( "wi" )) );
		}
	}
	
	protected function modListener_SweepstakeApp_registerView( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$fbUserData = $this->getFbUserDataFromRequest( $userRequest );
			$this->model->registerView( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "fbUserId" ), $userRequest->getHttpRequestParam( "userComeWayId" ), $fbUserData );
		}
	}
	
	protected function modListener_SweepstakeApp_registerEnter( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$fbUserData = $this->getFbUserDataFromRequest( $userRequest );
			$this->model->registerEnter( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "fbUserId" ), $userRequest->getHttpRequestParam( "userComeWayId" ), $fbUserData );
		}
	}
	
	protected function modListener_SweepstakeApp_registerEmail( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$this->model->registerEmail( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ), $userRequest->getHttpRequestParam( "email" ) );
		}
	}
	
	protected function modListener_SweepstakeApp_registerTwitterPublish( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$this->model->registerTwitterPublish( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ), $userRequest->getHttpRequestParam( "wayId" ) );
		}
	}
	
	protected function modListener_SweepstakeApp_registerFacebookPublish( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$this->model->registerFacebookPublish( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ), $userRequest->getHttpRequestParam( "wayId" ), $userRequest->getHttpRequestParam( "noPoint" ) );
		}
	}
	
	protected function modListener_SweepstakeApp_registerInvitePublish( $userRequest ){
		if( GlobalHelper::isRequestAjax() ){
			$this->model->registerFacebookInvite( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ), $userRequest->getHttpRequestParam( "wayId" ), json_decode( $userRequest->getHttpRequestParam( "toUsers" ), true) );
		}
	}
	
	private function getFbUserDataFromRequest( $userRequest ){
		$fbUserData = array();
		if( !is_null( $userRequest->getHttpRequestParam( "email" ) ) ){
			$fbUserData[ "email" ] = $userRequest->getHttpRequestParam( "email" );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "first_name" ) ) ){
			$fbUserData[ "first_name" ] = $userRequest->getHttpRequestParam( "first_name" );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "last_name" ) ) ){
			$fbUserData[ "last_name" ] = $userRequest->getHttpRequestParam( "last_name" );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "gender" ) ) ){
			$fbUserData[ "gender" ] = $userRequest->getHttpRequestParam( "gender" );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "birthday" ) ) ){
			$fbUserData[ "birthday" ] = $userRequest->getHttpRequestParam( "birthday" );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "friends" ) ) ){
			$fbUserData[ "friends" ] = json_decode( $userRequest->getHttpRequestParam( "friends" ), true );
		}
		if( !is_null( $userRequest->getHttpRequestParam( "location" ) ) ){
			$fbUserData[ "location" ] = $userRequest->getHttpRequestParam( "location" );
		}
		
		return $fbUserData;
	}
}
?>