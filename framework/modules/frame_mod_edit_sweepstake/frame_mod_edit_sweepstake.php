<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";

require_once Config::getBaseDir() . "lib/sweepstake_manager.php";


class FrameModEditSweepstake extends FrameModParent {
	private static $modName = "EditSweepstake";
	public static function getName(){
		return self::$modName;
	}
	private static $modId = "EditSweepstake";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
		0 => array( "templateId"=>"*", "moduleState"=>"finish_creation_save", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" )
	);
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener__createSweepstake( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			if( !GlobalHelper::isRequestAjax() ){
				$this->model->startCreate();
			}
		}
	}
	
	protected function modListener__editSweepstake( $userRequest ){
		if( !GlobalHelper::isRequestAjax() ){
			$this->model->startEdit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
		}
	}
	
	protected function modListener_EditSweepstake_saveSweepstake( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$data = array();
			$data[ "title" ] = $userRequest->getHttpRequestParam( "title" );
			$data[ "desc" ] = $userRequest->getHttpRequestParam( "desc" );
			$data[ "restriction_text" ] = $userRequest->getHttpRequestParam( "restriction_text" );
			$data[ "start_date" ] = $userRequest->getHttpRequestParam( "start_date" );
			$data[ "end_date" ] = $userRequest->getHttpRequestParam( "end_date" );
			$data[ "enter_once_type" ] = $userRequest->getHttpRequestParam( "enter_once_type" );
			$data[ "bonus_point" ] = $userRequest->getHttpRequestParam( "bonus_point" );
			$data[ "bonus_enter_type" ] = $userRequest->getHttpRequestParam( "bonus_enter_type" );
			$data[ "publish_enter" ] = $userRequest->getHttpRequestParam( "publish_enter" );
			$data[ "min_age" ] = $userRequest->getHttpRequestParam( "min_age" );
			$data[ "create_date" ] = time();
			
			if( ($userRequest->getHttpRequestParam( "sweepstakeId" ) / 1) < 0 ){
				$id = $this->model->create( $data );
				$userRequest->changeHttpRequestParam( array( FrameworkRequest::$REQUEST_PARAM__actionId => "editSweepstake", "sweepstakeId" => $id ) );
			}else{
				$this->model->edit( $userRequest->getHttpRequestParam( "sweepstakeId" ), $data );
			}
		}
	}
	
	
	/*
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest["userLogin"], $userRequest["userPass"] );
	}
	*/
}
?>