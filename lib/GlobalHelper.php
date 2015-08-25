<?php
namespace lib{
	use framework\core\FrameworkRequest;
	
	class GlobalHelper{
		public static function isRequestAjax(){
			//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if( isset( $_REQUEST[ FrameworkRequest::$REQUEST_PARAM__ajaxCall ] ) ) {
				return true;
			}
	
			return false;
		}
	
		public static function generateRandomString( $length = 10 ) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
	
		public static function saveUploadedFile( $fieldName, $dir, $newName = null ){
			$fileName = is_null( $newName ) ? pathinfo($_FILES[ $fieldName ]["name"], PATHINFO_FILENAME) : $newName;
			$ex = pathinfo($_FILES[ $fieldName ]["name"], PATHINFO_EXTENSION);
			$fileName = $fileName . "." . $ex;
	
			if( file_exists( ($dir . $fileName) ) ){
				return null;
			}else{
				move_uploaded_file( $_FILES[ $fieldName ]["tmp_name"], ($dir . $fileName));
			}
	
			return $fileName;
		}
	
		public static function getClientIp() {
			$ipaddress = '';
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
	}	
}