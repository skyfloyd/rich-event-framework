<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";


class FrameModEditMessage extends FrameModParent {
	private static $modName = "EditMessage";
	public static function getName(){
		return self::$modName;
	}
	private static $modId = "EditMessage";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
	);
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener__editMessage( $userRequest ){
		if( !GlobalHelper::isRequestAjax() ){
			$this->model->startEdit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
		}
	}
	
	protected function modListener_EditMessage_saveMessage( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$data = array();
			$data[ "before_start_message" ] = $userRequest->getHttpRequestParam( "before_start_message" );
			$data[ "after_end_message" ] = $userRequest->getHttpRequestParam( "after_end_message" );
			$data[ "welcome_message" ] = $userRequest->getHttpRequestParam( "welcome_message" );
			$data[ "winner_message" ] = $userRequest->getHttpRequestParam( "winner_message" );
			
			$this->model->edit( $userRequest->getHttpRequestParam( "sweepstakeId" ), $data );
		}
	}
	
	
	/*
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest["userLogin"], $userRequest["userPass"] );
	}
	*/
}
?>