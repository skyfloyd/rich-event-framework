<?php
class FrameModMenu_View extends FrameModViewParent {
	public static $jsManagerName = "_frameModMenuManager";


	public static function getView( $model ){
		if( !GlobalHelper::isRequestAjax() ){
			return self::getHTMLResponse( $model );
		}else{
			return self::getAjaxResponse( $model );
		}
	}
	
	
	private static function getHTMLResponse( $model ){
		$frontendRoot = Config::getModulesUrl() . "frame_mod_menu/frontend/";
					
		$include = "<SCRIPT LANGUAGE='Javascript' SRC='" . $frontendRoot . "js/frame_mod_menu.js'></SCRIPT>
				 	<link rel='stylesheet' href='" . $frontendRoot . "css/frame_mod_menu.css' type='text/css' />";
		$script = "
var " . self::$jsManagerName . " = new FrameModMenu_manager();
" . self::$jsManagerName . ".startWork( 'frameModMenu_parentElement', 'frameMod_menu_selectedItem', 'frameMod_menu_item', 'frameMod_menu_item', '__frameMod_menu_itemParam__' );
";
		$html = "<div id='frameModMenu_parentElement' class='frameMod_menu_container'>";
		$items = $model->getItemsList();
		for( $i = 0; $i < count( $items ); $i++ ){
			$class = ($items[ $i ][ "selected" ] ? "frameMod_menu_selectedItem" : "frameMod_menu_item");
			$html .= "<div class='$class' name='frameMod_menu_item' id='" . $items[ $i ][ "action" ] . "__frameMod_menu_itemParam__" . $items[ $i ][ "param" ] . "'>" . $items[ $i ][ "cation" ] . "</div>";
		}
		$html .= "</div>";
		
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__READY );
		$res->setHtml( $html );
		$res->setScript( $script );
		$res->setImport( $include );
		
		return $res;
	}
	
	private static function getAjaxResponse( $model ){
		$res = new FrameworkResponse();
		$res->setStatus( FrameworkResponse::$STATUS__READY );
		$res->setJson( array() );
		
		return $res;
	}

}
?>