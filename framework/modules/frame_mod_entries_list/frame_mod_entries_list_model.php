<?php
class FrameModEntriesList_Model extends FrameModModelParent {
	public static $STATE__SHOW_LIST = "show_list";
	
	private $limitIndex = null;
	private $limitCount = null;
	private $limitCountDefaultValue = -1;
	
	private $sweepstakeId = null;
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
		
		$this->setNoAction();
	}
	
	
	public function setPageNumber( $pageNumber ){
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		if( $pageNumber < 1 ){
			$pageNumber = 1;
		}
		
		$this->limitIndex = $pageNumber - 1;
	}
	public function getPageNumber(){
		return ($this->limitIndex + 1);
	}
	
	public function setPageItemsCount( $count ){
		$this->limitCount = $count;
	}
	public function getPageItemsCount(){
		return $this->limitCount;
	}
	
	public function getListData(){
		$data = SweepstakeUser_table::getSweepstakeUser( array( "enter" => 1, "sweepstake_id" => $this->sweepstakeId ), $this->limitIndex, $this->limitCount );
		return $data;
	}
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	public function setSweepstakeId( $id ){
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		$this->sweepstakeId = $id;
	}
}
?>