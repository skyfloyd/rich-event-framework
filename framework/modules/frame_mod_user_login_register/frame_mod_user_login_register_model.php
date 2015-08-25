<?php
use framework\core\FrameworkUserManager;

class FrameModUserLoginRegister_Model {
		/*must be excluded to XML, to be configurable*/
	private $currentState = null;
	
	public static $STATE__NO_USER = "no_user";
	public static $STATE__ADMIN_LOGIN = "admin_login";
	public static $STATE__INCORRECT_LOGIN = "incorrect_login";
	
	
	public function getCurrentState(){
		return $this->currentState;
	}
	
	public function __construct(){
		$currentUser = FrameworkUserManager::getCurrentUserData();
		if( is_null( $currentUser ) ){
			$this->currentState = self::$STATE__NO_USER;
		}else{
			$this->currentState = self::$STATE__ADMIN_LOGIN;
		}
	}
	
	public function getLoginUserData(){
		$data = FrameworkUserManager::getCurrentUserData();
		return $data;
	}
	
	public function loginUser( $login, $pass ){
		$res = FrameworkUserManager::loginUser( $login, $pass );
		
		if( $res === false ){
			$this->currentState = self::$STATE__INCORRECT_LOGIN;
			return false;
		}else{
			$this->currentState = self::$STATE__ADMIN_LOGIN;
		}
		
		return true;
	}
	
	public function logoutUser(){
		$data = FrameworkUserManager::logoutUser();
		$this->currentState = self::$STATE__NO_USER;
	}
}
?>