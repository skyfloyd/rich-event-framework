<?php
namespace framework\core;

class FrameworkUserManager {
	public static $ROLE_GOD = 0;
	public static $ROLE_ADMIN = 1;
	public static $ROLE_VIEWER = 2;
	
	
	public static function getCurrentUserData(){
		if( isset( $_SESSION[ "currentUserData" ] ) ){
			return $_SESSION[ "currentUserData" ];
		}
		
		return null;
	}
	
	public static function loginUser( $login, $pass ){
		$res = FrameworkUser_table::login( $login, $pass );
		
		if( is_null( $res ) ){
			return false;
		}
		
		$_SESSION[ "currentUserData" ] = $res;
		return $res;
	}
	
	public static function logoutUser(){
		if( isset( $_SESSION[ "currentUserData" ] ) ){
			unset( $_SESSION[ "currentUserData" ] );
		}
	}
	
	public static function getCurrentUserRole(){
		$data = self::getCurrentUserData();
		if( is_null( $data ) ){
			return null;
		}
		
		return $data[ "role" ];
	}
}