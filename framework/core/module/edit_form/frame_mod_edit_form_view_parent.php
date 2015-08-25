<?php
abstract class FrameModEditFormViewParent extends FrameModViewParent {
	
//	abstract static protected function getHTMLResponse( $model );
//	abstract static protected function getAjaxResponse( $model );

	public static function getDataParamsList(){
		return array_keys( static::$dataStructure[ "data" ] );
	}
	
	protected static function getSimpleHtmlFormPart(){
		$view = "";
		foreach( static::$dataStructure[ "data" ] as $fildName => $fildParams ){
			$component = "";
			if( $fildParams[ "type" ] == "text" ){
				$component = "<input type='text' id='" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "' name='" . $fildName . "' value='' placeholder='' style='width: 500px;' />";
			}else
			if( $fildParams[ "type" ] == "textArea" ){
				$component = "<textarea id='" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "' name='" . $fildName . "' value='' placeholder='' style='width: 500px; height: 100px;' ></textarea>";
			}else
			if( $fildParams[ "type" ] == "file" ){
				$component = "<input type='file' id='" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "' name='" . $fildName . "' />";
			}
			
			if( isset( $fildParams[ "view" ] ) ){
				if(  $fildParams[ "view" ][ "type" ] = "img" ){
					$component = "<table><tr><td>" . $component . "</td><td><img style='height: 100px;' id='" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "_view' src='" . Config::getBaseURL() . "frontend/image/no_image.jpg' ></td></tr></table>";
				}
			}
			
			$view .= "<tr><td style='font-family: arial; font-size: 15px;'>" . $fildParams[ "name" ] . "</td><td>" . $component . "</td></tr>";
		}
		
		return $view;
	}
	
	protected static function setFormData( $data ){
		$view = "";
		foreach( static::$dataStructure[ "data" ] as $fildName => $fildParams ){
			if( $fildParams[ "type" ] == "text" || $fildParams[ "type" ] == "textArea" ){
				$view .= "
document.getElementById( '" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "' ).value = '" . addslashes( preg_replace( "/\r|\n/", "", $data[ $fildName ] ) ) . "';
";
			}else
			if( $fildParams[ "type" ] == "file" ){
				if( isset( $fildParams[ "view" ] ) ){
					if( $fildParams[ "view" ][ "type" ] = "img" ){
						if( trim( $data[ $fildName ] ) != "" ){
							$view .= "
document.getElementById( '" . static::$dataStructure[ "htmlIdPrefix" ] . $fildName . "_view' ).src = '" . addslashes( $data[ $fildName ] ) . "';
";
						}
					}
				}
			}
		}
		
		return $view;
	}
}
?>