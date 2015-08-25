<?php
namespace framework\core\module;

abstract class FrameModViewParent {
	
	protected static function getBasicAjaxResponse( $model ){
		$res = new FrameworkResponse();
		
		if( $model->isErrorState() ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => ($model->getModuleId() . "__" . $model->getActionName() . "__ERROR"), "moduleActionParam" => $model->getErrorMessage() );
			$res->setJson( $json );
			return $res;
		}else
		if( $model->isNotPermittedState() ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => ($model->getModuleId() . "__" . $model->getActionName() . "__NO_PERMISSION"), "moduleActionParam" => "You are not permitted to do this action" );
			$res->setJson( $json );
			return $res;
		}
		
		return null;
	}
//	abstract static protected function getHTMLResponse( $model );
//	abstract static protected function getAjaxResponse( $model );
}
?>