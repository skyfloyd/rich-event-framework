<?php
namespace framework\core\module;

use framework\core\FrameworkRequest;
use framework\core\FrameworkResponse;
use lib\GlobalHelper;

abstract class FrameModParent {
	protected $listenerMethodPrefix = "modListener_"; //this values are in core.js too
	protected $actionMethodPrefix = "modAction_";
	
	protected $model = null;
	
	abstract public function getResponse( $userRequest, $directAction = null );
	abstract protected function getWorkflow();
	
	protected function getResponseDefaultWork( $userRequest, $directAction = null ){
		$className = get_class( $this );
		$reflector = new \ReflectionClass( $className );
		$fn = $reflector->getFileName();
		//$dirName = $this->getDirName( dirname(__FILE__) );
		$dirName = $this->getDirName( dirname( $fn ) );
		$modelClass = $className . "_Model";
		$viewClass = $className . "_View";
		
		$this->model = new $modelClass( static::getModuleId(), $className, $dirName, $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__actionId ) );
		$methodCalled = $this->methodsCall( $userRequest, $directAction );
		//if( !$methodCalled && ajaxCall ) return nothing;
		
		return $this->chooseActionByModelStatus( $userRequest, $this->model, $this->getWorkflow(), $viewClass );
		//$view = FrameModUserLoginRegister_View::getView( $this->model );
	}
	
	protected function methodsCall( $userRequest, $directAction = null ){
		$methodCalled = false;
		
		$sourceActionId = $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__actionId );
		$sourceModuleId = $userRequest->getHttpRequestParam( FrameworkRequest::$REQUEST_PARAM__moduleId );

		
		$mod = false;
		$act = false;
		if( $sourceModuleId != null && $sourceModuleId != "" ){ // esi nayuma ete module idka requestum mihat stuguma listener method ka et modulein?
			$listenerMethodName = $this->listenerMethodPrefix . $sourceModuleId;
			if( method_exists( $this, $listenerMethodName ) ){
				$this->$listenerMethodName( $userRequest );
				$methodCalled = true;
			}
			
			$mod = true;
		}
		
		if( $sourceActionId != null && $sourceActionId != "" ){ // esi nayuma ete action idka requestum mihat stuguma listener method ka et actionin? entadrvuma vor action@ unikala linelu bolor modulneri mej
			$listenerMethodName = $this->listenerMethodPrefix . "_" . $sourceActionId;
			if( method_exists( $this, $listenerMethodName ) ){
				$this->$listenerMethodName( $userRequest );
				$methodCalled = true;
			}
			
			$act = true;
		}
		
		if( $mod && $act ){ // ete ham module id ham action idka mi hat el stuguma konkret et module id-ov et action id-in lsox ka?
			$listenerMethodName = $this->listenerMethodPrefix . $sourceModuleId . "_" . $sourceActionId;
			if( method_exists( $this, $listenerMethodName ) ){
				$this->$listenerMethodName( $userRequest );
				$methodCalled = true;
			}
		}
				
		if( $directAction != null ){ // isk esi konkret actiona vor@ uxvaca henc iran, aysinqn vochte inqna nstac lsum, ayl henc ran en kanchum vdrug otkudo nevazmis
			$actionMethodName = $this->actionMethodPrefix . $directAction;
					
			if( method_exists( $this, $actionMethodName ) ){
				$this->$actionMethodName( $userRequest );
				$methodCalled = true;
			}
		}
		
		return $methodCalled;
	}
	
	protected function chooseActionByModelStatus( $userRequest, $model, $actionMap, $viewClass ){
		$templatesMap = array( $userRequest->getTemplateId(), "*" );
		
		foreach( $templatesMap as $templ ){
			foreach( $actionMap as $action ){ //@ste hima stugvuma menak cherez model_state bayc piti stugvi naev cherez template_id
				if( $action[ "templateId" ] == $templ && $action[ "moduleState" ] == $model->getCurrentState() ){
					if( $action[ "action" ] == "changeTemplate" ){
						if( GlobalHelper::isRequestAjax() ){ //JS change template
							$res = new FrameworkResponse();
							$res->setStatus( FrameworkResponse::$STATUS__READY );
							$res->changeTemplate( ($action[ "actionParam" ] . "&" . $userRequest->getHttpRequestParamsStr( array( FrameworkRequest::$REQUEST_PARAM__templateId, FrameworkRequest::$REQUEST_PARAM__ajaxCall ) )) );
						}else{ //PHP change template
							$res = new FrameworkResponse();
							$res->setStatus( FrameworkResponse::$STATUS__CHANGE_TEMPLATE );
							$res->setData( $action[ "actionParam" ] );
						}
						
						return $res;
					}
				}
			}
		}
		
		return $viewClass::getView( $model ); //call_user_func
	}
	
	
	protected function getDirName( $fullPath ){
		$parts = explode( DIRECTORY_SEPARATOR, $fullPath );
		return $parts[ (count( $parts ) - 1) ];
	}
}
?>