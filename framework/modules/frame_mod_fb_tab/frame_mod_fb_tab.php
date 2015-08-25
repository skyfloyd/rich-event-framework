<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";

require_once Config::getBaseDir() . "lib/sweepstake_manager.php";

class FrameModFbTab extends FrameModParent {
	private static $modName = "Fb Tab";
	private static $modId = "FbTab";
	protected static function getModuleId(){
		return self::$modId;
	}
	public static function getName(){
		return self::$modName;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array(
	);
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	
	
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest->getHttpRequestParam( "userLogin" ), $userRequest->getHttpRequestParam( "userPass" ) );
	}
	
	protected function modListener__editAppTab( $userRequest ){
		$this->model->startEdit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_FbTab_saveEditAppTab( $userRequest ){
		$this->model->edit( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "imageField" ), $userRequest->getHttpRequestParam( "tabName" ), $userRequest->getHttpRequestParam( "appKey" ), $userRequest->getHttpRequestParam( "appSecret" ) );
	}
}