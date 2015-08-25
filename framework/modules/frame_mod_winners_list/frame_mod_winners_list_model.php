<?php
class FrameModWinnersList_Model extends FrameModModelParent {
	public static $STATE__MAIN_VIEW = "main_view";
	public static $STATE__SHOW_LIST = "show_list";
	public static $STATE__EMAIL_SENT = "email_sent";
	
	private $limitIndex = null;
	private $limitCount = null;
	private $limitCountDefaultValue = -1;
	
	private $sweepstakeId = null;
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
		
		$this->setPageItemsCount( $this->limitCountDefaultValue );
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
		$data = SweepstakeUser_table::getSweepstakeUser( array( "sweepstake_id" => $this->sweepstakeId, "winner" => "1" ), $this->limitIndex, $this->limitCount );
		return $data;
	}
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	
	public function mainView( $sweepstakeId ){
		$this->sweepstakeId = $sweepstakeId;
		$this->setCurrentState( self::$STATE__MAIN_VIEW );
	}
	
	public function refreshList( $sweepstakeId ){
		$this->sweepstakeId = $sweepstakeId;
		$this->setCurrentState( self::$STATE__SHOW_LIST );
	}
	
	public function chooseWinners( $sweepstakeId, $count ){
		$this->sweepstakeId = $sweepstakeId;
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		
		$sweepstakeData = Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
		SweepstakeUser_table::setWinners( $this->sweepstakeId, $count );
		$winners = $this->getListData();
		for( $i = 0; $i < count( $winners ); $i++ ){
			//mail( $winners[ $i ][ "email" ], ("You become winner in " . $sweepstakeData[ "title" ]), $sweepstakeData[ "winner_message" ], "Content-type: text/html; charset=iso-8859-1" );
		}
	}
	
	public function removeWinner( $sweepstakeId, $userId ){
		$this->sweepstakeId = $sweepstakeId;
		$this->setCurrentState( self::$STATE__SHOW_LIST );
		
		SweepstakeUser_table::removeWinners( $sweepstakeId, $userId );
	}
	
	public function emailWinner( $sweepstakeId, $userId ){
		$this->sweepstakeId = $sweepstakeId;
		$this->setCurrentState( self::$STATE__EMAIL_SENT );
		
		$sweepstakeData = Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
		$userFbData = FbUser_table::getUser( $userId );
		mail( $userFbData[ "email" ], ("You become winner in " . $sweepstakeData[ "title" ]), $sweepstakeData[ "winner_message" ], "Content-type: text/html; charset=UTF-8" );
		
	}
}
?>