<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";
require_once Config::getBaseDir() . "database_layer/tables/fb_user_table.php";

class FrameModWinnersList extends FrameModParent {
	private static $modName = "Winners List";
	public static function getName(){
		return self::$modName;
	}
	private static $modId = "WinnersList";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
	); // es cucakum karan view-in dimelu actionner@ chlinen
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	
	
	protected function modListener__winnersList( $userRequest ){
		$this->model->mainView( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_WinnersList_chooseWinners( $userRequest ){
		$this->model->chooseWinners( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "count" ) );
	}
	
	protected function modListener_WinnersList_emailWinners( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$this->model->emailWinner( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ) );
		}
	}
	
	protected function modListener_WinnersList_removeWinners( $userRequest ){
		$this->model->removeWinner( $userRequest->getHttpRequestParam( "sweepstakeId" ), $userRequest->getHttpRequestParam( "userId" ) );
	}
	
	protected function modListener_WinnersList_refreshWinnersList( $userRequest ){
		$this->model->refreshList( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
}
?>