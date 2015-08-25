<?php
class FrameModEditSweepstake_Model extends FrameModModelParent {
	public static $STATE__CREATE = "create";
	public static $STATE__EDIT = "edit";
	public static $STATE__FINISH_EDIT_SAVE = "finish_edit_save";
	public static $STATE__FINISH_CREATION_SAVE = "finish_creation_save";
	
	
	private $sweepstakeId = null;
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
		
		$this->setNoAction();
	}
	
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	public function create( $data ){
		$this->setCurrentState( self::$STATE__FINISH_CREATION_SAVE );
		
		$this->sweepstakeId = Sweepstake_table::createSweepstake( $data );
		$url = SweepstakeManager::createNewSweepstake( $this->sweepstakeId );
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "url" => $url ) );
		
		return $this->sweepstakeId;
	}
	
	public function edit( $id, $data ){
		$this->setCurrentState( self::$STATE__FINISH_EDIT_SAVE );
		$this->sweepstakeId = $id;
		
		Sweepstake_table::updateSweepstake( $id, $data );
	}
	
	public function startCreate(){
		$this->setCurrentState( self::$STATE__CREATE );
	}
	
	public function startEdit( $id ){
		$this->setCurrentState( self::$STATE__EDIT );
		$this->sweepstakeId = $id;
	}
	
	public function getCurrentSweepstakeData(){
		return Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
	}
}
?>