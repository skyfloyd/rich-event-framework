<?php
class FrameModSweepstakeOverview_Model extends FrameModModelParent {
		/*must be excluded to XML, to be configurable*/
	private $sweepstakeId = null;
	
	public static $STATE__START = "start";
	public static $STATE__ACTIVE = "active";
	public static $STATE__PASSIVE = "passive";
	
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
	}
	
	public function getSweepstakeStatistics(){
		$data = SweepstakeStatistics_table::getSweepstakeStatistics( $this->sweepstakeId );
		return $data;
	}
	
	public function isSweepstakeActive(){
		$data = Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
		if( $data[ "active" ] == "1" ){
			return true;
		}
		
		return false;
	}
	
	public function start( $seepstakeId ){
		$this->sweepstakeId = $seepstakeId;
		$this->setCurrentState( self::$STATE__START );
	}
	
	public function active( $seepstakeId ){
		$this->sweepstakeId = $seepstakeId;
		$this->setCurrentState( self::$STATE__ACTIVE );
		
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "active" => 1 ) );
	}
	
	public function passive( $seepstakeId ){
		$this->sweepstakeId = $seepstakeId;
		$this->setCurrentState( self::$STATE__PASSIVE );
		
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "active" => 0 ) );
	}
}