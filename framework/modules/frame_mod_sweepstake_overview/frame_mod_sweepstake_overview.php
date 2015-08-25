<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_statistics_table.php";

class FrameModSweepstakeOverview extends FrameModParent {
	private static $modId = "SweepstakeOverview";
	private static $modName = "Sweepstake Overview";
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
	
	protected function modListener__sweepstakeStatistics( $userRequest ){
		$this->model->start( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_SweepstakeOverview_activateSweepstake( $userRequest ){
		$this->model->active( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_SweepstakeOverview_passivateSweepstake( $userRequest ){
		$this->model->passive( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
}