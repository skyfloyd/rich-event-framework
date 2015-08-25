<?php
class FrameModSweepstakeList_Model extends FrameModModelParent {
	public static $STATE__SHOW_LIST = "show_list";
	public static $STATE__SHOW_ACTIVITY = "show_activity";
	public static $STATE__CREATE = "create";
	public static $STATE__CLONE = "clone";
	public static $STATE__EDIT = "edit";
	
	private $filter = null;
	private $currentFilterType = null;
	public static $FILTERS_ALL = 0;
	public static $FILTERS_ACTIVE = 1;
	public static $FILTERS_PRE_START = 2;
	public static $FILTERS_FINISH = 3;
	
	private $limitIndex = null;
	private $limitCount = null;
	private $limitCountDefaultValue = -1;
	
	private $sweepstakeId = null;
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
		
		$this->setFilter( self::$FILTERS_ALL );
		$this->setPageItemsCount( $this->limitCountDefaultValue );
		$this->setCurrentState( self::$STATE__SHOW_LIST );
	}
	
	public function setFilter( $type ){
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		$this->currentFilterType = self::$FILTERS_ALL;
		$this->filter = array();
		
		if( $type == self::$FILTERS_ACTIVE ){
			$this->currentFilterType = self::$FILTERS_ACTIVE;
			$this->filter = array( "active" => 1 );
		}else
		if( $type == self::$FILTERS_PRE_START ){
			$this->currentFilterType = self::$FILTERS_PRE_START;
			$this->filter = array( "pre-start" => 1 );
		}else
		if( $type == self::$FILTERS_FINISH ){
			$this->currentFilterType = self::$FILTERS_FINISH;
			$this->filter = array( "finish" => 1 );
		}
		
	}
	public function getFilterType(){
		return $this->currentFilterType;
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
		$data = Sweepstake_table::getSweepstakesWithActivity( $this->filter, $this->limitIndex, $this->limitCount );
		return $data;
	}
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	public function setSweepstakeActivitySweepstakesId( $id ){
		$this->sweepstakeId = $id;
		$this->setCurrentState( self::$STATE__SHOW_ACTIVITY );
	}
	public function getSweepstakeActivity(){
		return SweepstakeUser_table::getSweepstakeActivity( $this->sweepstakeId );
	}
	
	public function create(){
		$this->setCurrentState( self::$STATE__CREATE );
	}
	
	public function cloneSweepstake( $id ){
		$this->setCurrentState( self::$STATE__CLONE );
		
		$this->sweepstakeId = Sweepstake_table::cloneSweepstake( $id );
		$url = SweepstakeManager::createNewSweepstake( $this->sweepstakeId );
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "url" => $url ) );
		
		return $this->sweepstakeId;
	}
	
	public function edit( $id ){
		$this->setCurrentState( self::$STATE__EDIT );
		$this->sweepstakeId = $id;
	}
	
	public function remove( $id ){
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		Sweepstake_table::deleteSweepstake( $id );
	}
}
?>