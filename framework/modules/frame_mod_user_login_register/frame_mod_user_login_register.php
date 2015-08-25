<?php
use framework\core\module\FrameModParent;

class FrameModUserLoginRegister extends FrameModParent {
	private static $modName = "user_login_register";
	private static $modId = "user_login_register";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	public static function getName(){
		return self::$modName;
	}

	
	/* esi pii hetagayum gna XML */
	private $workflow = array( //qani vor jamanak chunem es pahi drutyamb template@ hashvi chem arni stugeluc
		0 => array( "templateId"=>"*", "moduleState"=>"no_user", "action"=>"view", "actionParam"=>"" ),
		1 => array( "templateId"=>"framework_template_default", "moduleState"=>"admin_login", "action"=>"changeTemplate", "actionParam"=>"framework_template_home" ),
		2 => array( "templateId"=>"framework_template_home", "moduleState"=>"no_user", "action"=>"changeTemplate", "actionParam"=>"framework_template_default" )
	); // es cucakum karan view-in dimelu actionner@ chlinen
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		$this->model = new FrameModUserLoginRegister_Model();

		$this->methodsCall( $userRequest, $directAction );
				
		return $this->chooseActionByModelStatus( $userRequest, $this->model, $this->workflow, "FrameModUserLoginRegister_View" );
		//$view = FrameModUserLoginRegister_View::getView( $this->model );
	}
	
	
	protected function modAction_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest->getHttpRequestParam( "userLogin" ), $userRequest->getHttpRequestParam( "userPass" ) );
	}
	
	protected function modListener_user_login_register_loginRequest( $userRequest ){
		$this->model->loginUser( $userRequest->getHttpRequestParam( "userLogin" ), $userRequest->getHttpRequestParam( "userPass" ) );
		$userRequest->changeHttpRequestParam( array( "userPass" => "" ) );
	}
	
	protected function modListener_user_login_register_logoutRequest( $userRequest ){
		$this->model->logoutUser();
	}
	
	
	/*
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