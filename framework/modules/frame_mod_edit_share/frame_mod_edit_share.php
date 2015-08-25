<?php
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_table.php";
require_once Config::getBaseDir() . "database_layer/tables/sweepstake_user_table.php";

require_once Config::getBaseDir() . "framework/core/module/edit_form/frame_mod_edit_form_parent.php";
require_once Config::getBaseDir() . "framework/core/module/edit_form/frame_mod_edit_form_view_parent.php";
require_once Config::getBaseDir() . "framework/core/module/edit_form/frame_mod_edit_form_model_parent.php";


class FrameModEditShare extends FrameModEditFormParent {
	private static $modId = "EditShare";
	private static $modName = "Edit Share";
	
	public static function getName(){
		return self::$modName;
	}
	protected static function getModuleId(){
		return self::$modId;
	}
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
	);
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	/* gnac parent - PORC */
	public function getResponse( $userRequest, $directAction = null ){
		return parent::getResponseDefaultWork( $userRequest, $directAction );
	}
	

	
	protected function modListener__editShare( $userRequest ){
		if( !GlobalHelper::isRequestAjax() ){
			$this->model->startEdit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
		}
	}
	
	protected function modListener_EditShare_save( $userRequest ){
		if( FrameworkUserManager::getCurrentUserRole() == FrameworkUserManager::$ROLE_VIEWER ){
			$this->model->setNotPermitted();
		}else{
			$data = $this->getDataFromRequest( $userRequest, $this->model );
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