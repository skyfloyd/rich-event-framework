<?php
class FrameModEditShare_Model extends FrameModModelParent {
	public static $STATE__EDIT = "edit";
	public static $STATE__FINISH_SAVE = "finish_save";
	
	
	private $sweepstakeId = null;
		
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
		
		$this->setNoAction();
	}
	
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	
	public function edit( $id, $data ){
		$this->sweepstakeId = $id;
		
		$keys = array_keys( $_FILES );
		if( count( $keys ) == 1 ){
			$fileName = GlobalHelper::saveUploadedFile( $keys[ 0 ], Config::getDataFileDir(), GlobalHelper::generateRandomString( 10 ) );
			if( is_null( $fileName ) ){
				$this->setError( "file upload problem" );
				return false;
			}
			$data[ "share_image" ] = Config::getDataFileUrl() . $fileName;
		}

		$this->setCurrentState( self::$STATE__FINISH_SAVE );
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, $data );
		return true;
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