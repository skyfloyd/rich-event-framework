<?php
class FrameModMenu_Model {
	private $currentState = null;	
	
	public static $STATE__HOME = "home";
	public static $STATE__CHANGE_PASS = "change_pass";
	public static $STATE__CREATE = "create";
	public static $STATE__STATISTICS = "statistics";
	public static $STATE__ENTRIES = "entries";
	public static $STATE__EDIT = "edit";
	public static $STATE__MESSAGES = "messages";
	public static $STATE__WINNERS = "winners";
	public static $STATE__SHARE = "share";
	public static $STATE__FB_TAB = "fb_tab";

	private $currentItemsList = null;
	
	private $itemsList_main = array( array( "cation" => "Sweepstakes", "action" => "sweepstakeList", "param" => "", "selected" => false ) ); 
									 //array( "cation" => "Change Password", "action" => "changePass", "param" => "", "selected" => false ) );
	private $itemsList_create = array( array( "cation" => "Home", "action" => "home", "param" => "", "selected" => false ),
									 array( "cation" => "New", "action" => "new", "param" => "", "selected" => false ) );
	private $itemsList_info = array( array( "cation" => "Home", "action" => "home", "param" => "", "selected" => false ),
									 array( "cation" => "Overview", "action" => "sweepstakeStatistics", "param" => "", "selected" => false ),
									 array( "cation" => "Entries", "action" => "entersList", "param" => "", "selected" => false ),
									 array( "cation" => "Edit", "action" => "editSweepstake", "param" => "", "selected" => false ),
									 array( "cation" => "Messages", "action" => "editMessage", "param" => "", "selected" => false ),
									 array( "cation" => "Winners", "action" => "winnersList", "param" => "", "selected" => false ),
									 array( "cation" => "Share", "action" => "editShare", "param" => "", "selected" => false ),
									 array( "cation" => "Facebook Page Tab", "action" => "editAppTab", "param" => "", "selected" => false ) );
	
	private $sweepstakeId = null;
	
	public function getCurrentState(){
		return $this->currentState;
	}
	
	public function __construct(){
		$this->changeCurrentState( self::$STATE__HOME );
		
	}
	
	private function selectItemByAction( $action ){
		$find = false;
		for( $i = 0; $i < count( $this->currentItemsList ); $i++ ){
			if( $this->currentItemsList[ $i ][ "action" ] == $action ){
				$this->currentItemsList[ $i ][ "selected" ] = true;
				$find = true;
			}
		}
		
		if( !$find ){
			$this->currentItemsList[ 0 ][ "selected" ] = true;
		}
	}
	
	private function changeItemsParams( $params ){
		for( $i = 0; $i < count( $this->currentItemsList ); $i++ ){
			$this->currentItemsList[ $i ][ "param" ] = $params;
		}
	}
	
	private function changeCurrentState( $state ){
		$this->currentState = $state;
		
		if( $this->currentState == self::$STATE__HOME || $this->currentState == self::$STATE__CHANGE_PASS ){
			$this->currentItemsList = $this->itemsList_main;
			
			if( $this->currentState == self::$STATE__HOME ){
				$this->selectItemByAction( "sweepstakeList" );
			}
/*			else
			if( $this->currentState == self::$STATE__CHANGE_PASS ){
				$this->selectItemByAction( "changePass" );
			} */
		}else
		if( $this->currentState == self::$STATE__CREATE ){
			$this->currentItemsList = $this->itemsList_create;
			$this->selectItemByAction( "new" );
		}else
		if( $this->currentState == self::$STATE__STATISTICS || $this->currentState == self::$STATE__ENTRIES || $this->currentState == self::$STATE__EDIT || $this->currentState == self::$STATE__MESSAGES || $this->currentState == self::$STATE__WINNERS || $this->currentState == self::$STATE__SHARE || $this->currentState == self::$STATE__FB_TAB ){
			$this->currentItemsList = $this->itemsList_info;
			
			if( $this->currentState == self::$STATE__STATISTICS ){
				$this->selectItemByAction( "sweepstakeStatistics" );
			}else
			if( $this->currentState == self::$STATE__ENTRIES ){
				$this->selectItemByAction( "entersList" );
			}else
			if( $this->currentState == self::$STATE__EDIT ){
				$this->selectItemByAction( "editSweepstake" );
			}else
			if( $this->currentState == self::$STATE__MESSAGES ){
				$this->selectItemByAction( "editMessage" );
			}else
			if( $this->currentState == self::$STATE__WINNERS ){
				$this->selectItemByAction( "winnersList" );
			}else
			if( $this->currentState == self::$STATE__SHARE ){
				$this->selectItemByAction( "editShare" );
			}else
			if( $this->currentState == self::$STATE__FB_TAB ){
				$this->selectItemByAction( "editAppTab" );
			}
			
			$this->changeItemsParams( ("sweepstakeId=" . self::getSweepstakeId()) );
		}
	}
	
	
	public function getItemsList(){
		return $this->currentItemsList;
	}
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	public function create(){
		$this->changeCurrentState( self::$STATE__CREATE );
	}
	
	public function edit( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__EDIT );
	}
	
	public function editMessage( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__MESSAGES );
	}
	
	public function entries( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__ENTRIES );
	}
	
	public function pageTab( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__FB_TAB );
	}
	
	public function share( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__SHARE );
	}
	
	public function statistics( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__STATISTICS );
	}
	
	public function winners( $id ){
		$this->sweepstakeId = $id;
		$this->changeCurrentState( self::$STATE__WINNERS );
	}
}
?>