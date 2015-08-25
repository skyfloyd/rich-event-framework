<?php
abstract class FrameModEditFormParent extends FrameModParent{
	protected function getDataFromRequest( $userRequest, $model ){
		$viewClass = $model->getModuleClassName() . "_View";
		
		$data = array();
		$params = $viewClass::getDataParamsList();
		for( $i = 0; $i < count( $params ); $i++ ){
			$data[ $params[ $i ] ] = $userRequest->getHttpRequestParam( $params[ $i ] );
		}
		
		return $data;
	}
}
?>