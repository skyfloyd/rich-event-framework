<?php
class FrameModEditShare_View extends FrameModEditFormViewParent {
	public static $jsManagerName = "_frameModEditShareManager";

	protected static $dataStructure = array(
										"htmlIdPrefix" => "frameModEditShare_",
										"data" => array(
											"share_image" => array( "type" => "file", "name" => "Facebook Publish image", "check" => array(), "view" => array("type" => "img", "size" => "100x100") ),
											"share_title" => array( "type" => "text", "name" => "Facebook Publish title", "check" => array( "require" ) ),
											"share_desc" => array( "type" => "textArea", "name" => "Facebook Publish description", "check" => array() ),
											"share_ivite" => array( "type" => "text", "name" => "Facebook Invite message", "check" => array( "require" ) ),
											"share_twitter" => array( "type" => "text", "name" => "Twitter Publish message", "check" => array() )
										)
									);
									

	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__EDIT ){
			$frontendRoot = Config::getModulesUrl() . $model->getModuleDirName() . "/frontend/";
						
			$include = "
<SCRIPT LANGUAGE='Javascript' SRC='" . Config::getBaseURL() . "frontend/js/framework/core/module/edit_form/frame_mod_edit_form_view_parent.js'></SCRIPT>
<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/" .  $model->getModuleDirName() . ".js'></SCRIPT>
<link rel='stylesheet' href='" . $frontendRoot . "css/" .  $model->getModuleDirName() . ".css' type='text/css' />";
						

				
			$html = "
<div>
<input type='hidden' id='frameModEditMessage_sweepstakeId' value='-1' />
<table cellpadding='10' cellspacing='10'>" . 
	self::getSimpleHtmlFormPart() .
"	<tr><td colspan='2'><div id='frameModEditMessage_status'></div></td></tr>
	<tr><td colspan='2' align='center'><input type='button' value='save' id='frameModEditMessage_submit' style='width: 100px;'></td></tr>
</table>
</div>";

			$sweepstakeData = $model->getCurrentSweepstakeData();
			$script = "
document.getElementById( 'frameModEditMessage_sweepstakeId' ).value = '" . $model->getSweepstakeId() . "';
" . self::setFormData( $sweepstakeData ) . "

var " . self::$jsManagerName . " = new " . $model->getModuleClassName() . "_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', 'frameModEditMessage_sweepstakeId', 'frameModEditMessage_submit', 'frameModEditMessage_status', JSON.parse( '" . json_encode( self::$dataStructure  ) . "' ) );
";

			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$res->setHtml( $html );
			$res->setScript( $script );
			$res->setImport( $include );
		}
		
		return $res;
	}
	
	private static function getAjaxResponse( $model ){
		$parentResponse = parent::getBasicAjaxResponse( $model );
		if( !is_null( $parentResponse ) ){
			return $parentResponse;
		}
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
		
		$moduleClass = $model->getModuleClassName() . "_Model";
		
		if( $model->getCurrentState() == $moduleClass::$STATE__FINISH_SAVE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "saveSuccess", "moduleActionParam" => "" );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>