<?php
class FrameModEditSweepstake_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModEditSweepstakeManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$res = new FrameworkResponse();
		
		if( $model->getCurrentState() == FrameModEditSweepstake_Model::$STATE__CREATE || $model->getCurrentState() == FrameModEditSweepstake_Model::$STATE__EDIT ){
			$frontendRoot = Config::getModulesUrl() . "frame_mod_edit_sweepstake/frontend/";
						
			$include = "
					
					<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_edit_sweepstake.js'></SCRIPT>
					<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_edit_sweepstake.css' type='text/css' />
					<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/jquery.datetimepicker.js'></SCRIPT>
					<link rel='stylesheet' href='" . $frontendRoot . "css/jquery.datetimepicker.css' type='text/css' />
					<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/tiny.editor.packed.js'></SCRIPT>
					<link rel='stylesheet' href='" . $frontendRoot . "css/tinyeditor.css' type='text/css' />
					<SCRIPT LANGUAGE='Javascript' SRC='" . Config::getBaseURL() . "frontend/lib/bootstrap-select/js/bootstrap-select.js'></SCRIPT>
					<link rel='stylesheet' href='" . Config::getBaseURL() . "frontend/lib/bootstrap-select/css/bootstrap-select.css' type='text/css' />
					<SCRIPT LANGUAGE='Javascript' SRC='" . Config::getBaseURL() . "frontend/lib/angular-wysiwyg/js/bootstrap-colorpicker-module.js'></SCRIPT>
					<SCRIPT LANGUAGE='Javascript' SRC='" . Config::getBaseURL() . "frontend/lib/angular-wysiwyg/js/angular-wysiwyg.js'></SCRIPT>
					<link rel='stylesheet' href='" . Config::getBaseURL() . "frontend/lib/angular-wysiwyg/css/style.css' type='text/css' />
					<script>
						var FrameModEditSweepstake_ModuleObj = angular.module('FrameModEditSweepstake_Module', ['myFrameworkCoreModule', 'colorpicker.module', 'wysiwyg.module']);
					</script>
					<style>
							@charset \"UTF-8\";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}
					</style>";
			$script = "
$('#frameModEditSweepstake_startDate').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	FrameModEditSweepstake_manager.getStringFromTime( new Date() )
});
$('#frameModEditSweepstake_startDate').datetimepicker({value: FrameModEditSweepstake_manager.getStringFromTime( new Date() ),step:10});

$('#frameModEditSweepstake_endDate').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate: FrameModEditSweepstake_manager.getStringFromTime( new Date() )
});
$('#frameModEditSweepstake_endDate').datetimepicker({value: FrameModEditSweepstake_manager.getStringFromTime( new Date() ),step:10});
			";

				
			$html = "
<div class='container-fluid' style='width: 80%;' ng-app='FrameModEditSweepstake_Module' data-ng-init=\"datatext=''\">
<input type='hidden' id='frameModEditSweepstake_sweepstakeId' value='-1' />
<form class='container-fluid form-horizontal' role='form'>
	<div class='form-group'>
		<label for='frameModEditSweepstake_title' class='col-sm-2 control-label'>Title</label>
		<div class='col-sm-10'>
			<input type='text' class='form-control' id='frameModEditSweepstake_title' placeholder='sweepstake title'>
		</div>
	</div>
	<div class='form-group'>
		<label for='frameModEditSweepstake_desc' class='col-sm-2 control-label'>Description</label>
		<div class='col-sm-10'>
			<wysiwyg textarea-id='frameModEditSweepstake_desc' textarea-class='form-control'  textarea-height='180px' textarea-name='textareaQuestion' ng-model='datatext' enable-bootstrap-title='true'></wysiwyg>
		</div>
	</div>
	<div class='form-group'>
		<label for='frameModEditSweepstake_restricText' class='col-sm-2 control-label'>Restrictions</label>
		<div class='col-sm-10'>
			<input type='text' class='form-control' id='frameModEditSweepstake_restricText' placeholder='Age restriction text for user'>
		</div>
	</div>
	<div class='form-group'>
		<label for='frameModEditSweepstake_startDate' class='col-sm-2 control-label'>Start Date</label>
		<div class='col-sm-10'>
			<input type='text' class='form-control' id='frameModEditSweepstake_startDate' style='width: 200px;'>
		</div>
	</div>
	<div class='form-group'>
		<label for='frameModEditSweepstake_endDate' class='col-sm-2 control-label'>End Date</label>
		<div class='col-sm-10'>
			<input type='text' class='form-control' id='frameModEditSweepstake_endDate' style='width: 200px;'>
		</div>
	</div>
	<div class='form-group'>
		<label class='col-sm-2 control-label'>Entry Settings</label>
		<div class='col-sm-10'>
			<div>
				<label style='font-weight: normal;'>
					Only allow users to enter once per <select id='frameModEditSweepstake_enterOnceType' class='selectpicker'><option value='0'>Facebook User</option><option value='1'>Facebook User per day</option></select>
				</label>
			</div>
			<div>
				<label style='font-weight: normal;'>
					Award <input type='number' id='frameModEditSweepstake_bonusPoint' value='1' class='form-control' style='width: 70px; display: unset;' min='0'> bonus entries per <select id='frameModEditSweepstake_bonusEnterType' class='selectpicker'><option value='0'>Facebook friend that enters after entrant.</option><option value='1'>User that clicks and enters from entrant's shared link.</option></select>
				</label>
			</div>
			<div class='checkbox'>
				<label>
					<input type='checkbox' id='frameModEditSweepstake_publishEnter'> Automatically post Opengraph Enter action to user's Facebook Timeline
				</label>
			</div>
		</div>
	</div>
	<div class='form-group'>
		<label for='frameModEditSweepstake_minAge' class='col-sm-2 control-label'>Age Restriction</label>
		<div class='col-sm-10'>
			<div class='input-group'>
				<span class='input-group-addon'>
					<input type='checkbox'>
				</span>
				<select  class='form-control selectpicker' id='frameModEditSweepstake_minAge'><option value='0'>No Restriction</option><option value='13'>13 Year</option><option value='18'>18 Year</option><option value='21'>21 Year</option></select>
			</div>
		</div>
	</div>
	<div class='form-group'><div class='col-sm-11 col-sm-offset-1'><div class='alert alert-danger' role='alert' id='frameModEditSweepstake_status'></div></div></div>
	<div class='form-group'><div class='col-sm-2' style='text-align: right;'><button id='frameModEditSweepstake_submit' class='btn btn-success'>Save</button></div></div>
</form>
</div>";
			if( $model->getCurrentState() == FrameModEditSweepstake_Model::$STATE__EDIT ){
				$sweepstakeData = $model->getCurrentSweepstakeData();
$script .= "
document.getElementById( 'frameModEditSweepstake_sweepstakeId' ).value = '" . $model->getSweepstakeId() . "';
document.getElementById( 'frameModEditSweepstake_title' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "title" ] ) ) . "';
//document.getElementById( 'frameModEditSweepstake_desc' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "desc" ] ) ) . "';
document.getElementById( 'frameModEditSweepstake_restricText' ).value = '" . addslashes( $sweepstakeData[ "restriction_text" ] ) . "';
document.getElementById( 'frameModEditSweepstake_enterOnceType' ).value = '" . addslashes( $sweepstakeData[ "enter_once_type" ] ) . "';
document.getElementById( 'frameModEditSweepstake_bonusPoint' ).value = '" . addslashes( $sweepstakeData[ "bonus_point" ] ) . "';
document.getElementById( 'frameModEditSweepstake_bonusEnterType' ).value = '" . addslashes( $sweepstakeData[ "bonus_enter_type" ] ) . "';
document.getElementById( 'frameModEditSweepstake_publishEnter' ).checked = " . ($sweepstakeData[ "publish_enter" ] == "1" ? "true" : "false") . ";
document.getElementById( 'frameModEditSweepstake_minAge' ).value = '" . addslashes( $sweepstakeData[ "min_age" ] ) . "';
document.getElementById( 'frameModEditSweepstake_status' ).value = '';

$('#frameModEditSweepstake_startDate').datetimepicker({value: FrameModEditSweepstake_manager.getStringFromTime( new Date( " . ($sweepstakeData[ "start_date" ] * 1000) . " ) ),step:10});
$('#frameModEditSweepstake_endDate').datetimepicker({value: FrameModEditSweepstake_manager.getStringFromTime( new Date( " . ($sweepstakeData[ "end_date" ] * 1000) . " ) ),step:10});
";
			}
			
$script .= "

		
$( document.getElementById( 'frameModEditSweepstake_enterOnceType' ) ).selectpicker();
$( document.getElementById( 'frameModEditSweepstake_bonusEnterType' ) ).selectpicker();

var editor1 = {};
/*
var editor1 = new TINY.editor.edit('editor1', {
	id: 'frameModEditSweepstake_desc',
	width: 600,
	height: 100,
	cssclass: 'tinyeditor',
	controlclass: 'tinyeditor-control',
	rowclass: 'tinyeditor-header',
	dividerclass: 'tinyeditor-divider',
	controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
		'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
		'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
		'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
	footer: true,
	fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
	xhtml: true,
	bodyid: 'editor',
	footerclass: 'tinyeditor-footer',
	toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
	resize: {cssclass: 'resize'}
});
*/

var " . self::$jsManagerName . " = new FrameModEditSweepstake_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', 'frameModEditSweepstake_sweepstakeId', 'frameModEditSweepstake_submit', 'frameModEditSweepstake_status', editor1 );
";
			
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$res->setHtml( $html );
			$res->setScript( $script );
			$res->setImport( $include );
		}else{
			$res->setStatus( FrameworkResponse::$STATUS__CHANGE_MODULE );
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
		
		if( $model->getCurrentState() == FrameModEditSweepstake_Model::$STATE__FINISH_CREATION_SAVE || $model->getCurrentState() == FrameModEditSweepstake_Model::$STATE__FINISH_EDIT_SAVE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "sweepstakeSaveSuccess", "moduleActionParam" => "" );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>