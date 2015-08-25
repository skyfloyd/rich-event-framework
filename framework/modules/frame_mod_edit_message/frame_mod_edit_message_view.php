<?php
class FrameModEditMessage_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModEditMessageManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$res = new FrameworkResponse();
		
		if( $model->getCurrentState() == FrameModEditMessage_Model::$STATE__EDIT ){
			$frontendRoot = Config::getModulesUrl() . "frame_mod_edit_message/frontend/";
						
			$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_edit_message.js'></SCRIPT>
						<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_edit_message.css' type='text/css' />
						<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/tiny.editor.packed.js'></SCRIPT>
						<link rel='stylesheet' href='" . $frontendRoot . "css/tinyeditor.css' type='text/css' />";

				
			$html = "
<div>
<input type='hidden' id='frameModEditMessage_sweepstakeId' value='-1' />
<table cellpadding='10' cellspacing='10'>
	<tr><td style='font-family: arial; font-size: 15px;'>Before Start Message</td><td><textarea id='frameModEditMessage_start'></textarea></td></tr>
	<tr><td style='font-family: arial; font-size: 15px;'>After End Message</td><td><textarea id='frameModEditMessage_end'></textarea></td></tr>
	<tr><td style='font-family: arial; font-size: 15px;'>Welcome Message</td><td><textarea id='frameModEditMessage_welcome'></textarea></td></tr>
	<tr><td style='font-family: arial; font-size: 15px;'>Winner Message</td><td><textarea id='frameModEditMessage_winner'></textarea></td></tr>
	<tr><td colspan='2'><div id='frameModEditMessage_status'></div></td></tr>
	<tr><td colspan='2' align='center'><input type='button' value='save' id='frameModEditMessage_submit' style='width: 100px;'></td></tr>
</table>
</div>";


				$sweepstakeData = $model->getCurrentSweepstakeData();
$script = "
document.getElementById( 'frameModEditMessage_sweepstakeId' ).value = '" . $model->getSweepstakeId() . "';
document.getElementById( 'frameModEditMessage_start' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "before_start_message" ] ) ) . "';
document.getElementById( 'frameModEditMessage_end' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "after_end_message" ] ) ) . "';
document.getElementById( 'frameModEditMessage_welcome' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "welcome_message" ] ) ) . "';
document.getElementById( 'frameModEditMessage_winner' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $sweepstakeData[ "winner_message" ] ) ) . "';

var editor1 = new TINY.editor.edit('editor1', {
	id: 'frameModEditMessage_start',
	width: 584,
	height: 175,
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

var editor2 = new TINY.editor.edit('editor2', {
	id: 'frameModEditMessage_end',
	width: 584,
	height: 175,
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

var editor3 = new TINY.editor.edit('editor3', {
	id: 'frameModEditMessage_welcome',
	width: 584,
	height: 175,
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

var editor4 = new TINY.editor.edit('editor4', {
	id: 'frameModEditMessage_winner',
	width: 584,
	height: 175,
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

var " . self::$jsManagerName . " = new FrameModEditMessage_manager();
" . self::$jsManagerName . ".startWork( '" . $model->getModuleId() . "', 'frameModEditMessage_sweepstakeId', 'frameModEditMessage_submit', 'frameModEditMessage_status', editor1, editor2, editor3, editor4 );
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
		
		if( $model->getCurrentState() == FrameModEditMessage_Model::$STATE__FINISH_EDIT_SAVE ){
			$res->setStatus( FrameworkResponse::$STATUS__READY );
			$json = array( "moduleAction" => "messageSaveSuccess", "moduleActionParam" => "" );
			$res->setJson( $json );
		}
		
		return $res;
	}

}
?>