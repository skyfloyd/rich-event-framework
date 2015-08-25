<?php

class FrameModMenu extends FrameModParent {
	private static $modName = "Menu";
	private static $modId = "Menu";
	protected static function getModuleId(){
		return self::$modId;
	}
	
	public static function getName(){
		return self::$modName;
	}
	
	/* esi pii hetagayum gna XML */
	private $workflow = array();
	protected function getWorkflow(){
		return $this->workflow;
	}
	
	
	public function getResponse( $userRequest, $directAction = null ){
		$this->model = new FrameModMenu_Model();

		$methodCalled = $this->methodsCall( $userRequest, $directAction );
		//if( !$methodCalled && ajaxCall ) return nothing;
		
		return $this->chooseActionByModelStatus( $userRequest, $this->model, $this->workflow, "FrameModMenu_View" );
		//$view = FrameModUserLoginRegister_View::getView( $this->model );
	}
	
	protected function modListener__createSweepstake( $userRequest ){
		$this->model->create();
	}
	
	protected function modListener__editSweepstake( $userRequest ){
		$this->model->edit( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__editMessage( $userRequest ){
		$this->model->editMessage( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__entersList( $userRequest ){
		$this->model->entries( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__editAppTab( $userRequest ){
		$this->model->pageTab( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__editShare( $userRequest ){
		$this->model->share( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__sweepstakeStatistics( $userRequest ){
		$this->model->statistics( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
	}
	
	protected function modListener__winnersList( $userRequest ){
		$this->model->winners( $userRequest->getHttpRequestParam( "sweepstakeId" ) );
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