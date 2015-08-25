<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";
require_once Config::getBaseDir() . "database_layer/tables/fb_user_table.php";

class FrameModEntriesList extends FrameModParent {
	private static $modName = "EntriesList";
	public static function getName(){
		return self::$modName;
	}
	private static $modId = "EntriesList";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array(); // es cucakum karan view-in dimelu actionner@ chlinen
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener_Menu_entersList( $userRequest ){
		$this->model->setSweepstakeId( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_EntriesList_refreshList( $userRequest ){
		$this->model->setSweepstakeId( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	/*
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest["userLogin"], $userRequest["userPass"] );
	}
	*/
}
?>