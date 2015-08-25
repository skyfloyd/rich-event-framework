<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";

require_once Config::getBaseDir() . "lib/sweepstake_manager.php";

class FrameModSweepstakeList extends FrameModParent {
	private static $modName = "SweepstakeList";
	public static function getName(){
		return self::$modName;
	}
	private static $modId = "SweepstakeList";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
		0 => array( "templateId"=>"*", "moduleState"=>"create", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" ),
		1 => array( "templateId"=>"*", "moduleState"=>"clone", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" ),
		2 => array( "templateId"=>"*", "moduleState"=>"edit", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" )
	); // es cucakum karan view-in dimelu actionner@ chlinen
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener_SweepstakeList_refreshList( $userRequest ){
		if( !is_null( $userRequest->getHttpRequestParam( "filter" ) ) ){
			if( $userRequest->getHttpRequestParam( "filter" ) == "all" ){
				$this->model->setFilter( FrameModSweepstakeList_Model::$FILTERS_ALL );
			}else
			if( $userRequest->getHttpRequestParam( "filter" ) == "active" ){
				$this->model->setFilter( FrameModSweepstakeList_Model::$FILTERS_ACTIVE );
			}else
			if( $userRequest->getHttpRequestParam( "filter" ) == "pre-start" ){
				$this->model->setFilter( FrameModSweepstakeList_Model::$FILTERS_PRE_START );
			}else
			if( $userRequest->getHttpRequestParam( "filter" ) == "finish" ){
				$this->model->setFilter( FrameModSweepstakeList_Model::$FILTERS_FINISH );
			}
		}
	}
	
	protected function modListener_SweepstakeList_createSweepstake( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$this->model->create();
		}
	}
	
	protected function modListener_SweepstakeList_cloneSweepstake( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$id = $this->model->cloneSweepstake( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
			$userRequest->changeHttpRequestParam( array( FrameworkRequest::$REQUEST_PARAM__actionId => "editSweepstake", "sweepstakeId" => $id ) );
		}
	}
	
	protected function modListener_SweepstakeList_editSweepstake( $userRequest ){
		$this->model->edit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener_SweepstakeList_removeSweepstake( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$this->model->remove( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
		}
	}
	
	/*
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest["userLogin"], $userRequest["userPass"] );
	}
	
	protected function modListener_photogallery_getInsertPhotoView( $userRequest ){
		$this->model->setViewType( FrameModPhotogallery_Model::$viewType_insertPhotoView );
	}
	
	protected function modListener_photogallery_insertPhoto( $userRequest ){
		$res = $this->model->setNewFile( $_FILES['userPhoto'], $userRequest["userDesc"] );

		if( $res )
			$this->model->setViewType( FrameModPhotogallery_Model::$viewType_global );
	}
	*/
}
?>