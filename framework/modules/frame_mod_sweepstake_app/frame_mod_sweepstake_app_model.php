<?php
class FrameModSweepstakeApp_Model extends FrameModModelParent {	
	public static $STATE__START = "start";
	public static $STATE__NO_ACTION = "no_action";
	public static $STATE__REGISTER_VIEW = "registerView";
	public static $STATE__REGISTER_ENTER = "registerEnter";
	public static $STATE__REGISTER_EMAIL = "registerEmail";
	public static $STATE__REGISTER_FACEBOOK_PUBLISH = "registerFacebookPublish";
	public static $STATE__REGISTER_TWITTER_PUBLISH = "registerTwitterPublish";
	public static $STATE__REGISTER_FACEBOOK_INVITE = "registerFacebookInvite";
	
	
	private $appPoint = 0;
	private $sweepstakeId = null;
	private $userComeWayId = null;
	private $userId = null;
	
	private static $publishType_facebookPublish = 1;
	private static $publishType_facebookInvite = 2;
	private static $publishType_twitterPublish = 3;
	
	private static $publishPointInterval = 86400; //(60 * 60 * 24);
	

	
	public function __construct( $moduleId, $className, $dirName ){
		parent::__construct( $moduleId, $className, $dirName );
		
		$this->setCurrentState( self::$STATE__NO_ACTION );
	}
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
	
	public function getUserComeWayId(){
		return $this->userComeWayId;
	}
	
	public function getAddPoint(){
		return $this->appPoint;
	}
	
	public function startApp( $sweepstakeId, $comeWayId = "" ){
		$this->setCurrentState( self::$STATE__START );
		$this->sweepstakeId = $sweepstakeId;
		$this->userComeWayId = $comeWayId;
	}
	
	public function getSweepstakeData(){
		return Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
	}
	
	public function getSweepstakeUserData(){
		$data = SweepstakeUser_table::getSweepstakeUser( array("sweepstake_id" => $this->sweepstakeId, "user_id" => $this->userId) );
		return $data[ 0 ];
	}
	
	public function registerView( $sweepstakeId, $fbUserId, $comeWayId, $fbUserData ){
		$this->setCurrentState( self::$STATE__REGISTER_VIEW );
		$data = $fbUserData;
		$data[ "fb_user_id" ] = $fbUserId;
		$this->userId = FbUser_table::setUser( $fbUserId, $data );
		$this->sweepstakeId = $sweepstakeId;
		
		SweepstakeUser_table::registerView( $this->userId, $this->sweepstakeId, array("ip" => GlobalHelper::getClientIp()) );
		
		SweepstakeStatistics_table::registerView( $this->sweepstakeId );
	}
	
	public function registerEnter( $sweepstakeId, $fbUserId, $comeWayId, $fbUserData ){
		$this->setCurrentState( self::$STATE__REGISTER_ENTER );
		$data = $fbUserData;
		$data[ "fb_user_id" ] = $fbUserId;
		$this->userId = FbUser_table::setUser( $fbUserId, $data );
		$this->sweepstakeId = $sweepstakeId;
		
		
		$fList = "";
		for( $i = 0; $i < count( $fbUserData[ "friends" ] ); $i++ ){
			$fList .= ", '" . $fbUserData[ "friends" ][ $i ][ "id" ] . "'";
		}
		if( $fList != "" ){
			$fList = substr( $fList, 1 );
		}
		$sweepstakeData = $this->getSweepstakeData();
		if( $sweepstakeData[ "bonus_enter_type" ] == "0" && $fList != "" ){
			SweepstakeUser_table::addPointsByFbUsers( $fList, $this->sweepstakeId, $sweepstakeData[ "bonus_point" ] );
		}else{
			if( $comeWayId != "" && $comeWayId != "0" ){
				$data = SweepstakeUser_table::getSweepstakeUser( array("sweepstake_id" => $this->sweepstakeId, "user_id" => $this->userId) );
				if( count( $data ) == 0 || $data[ 0 ][ "come_way" ] == "" || $data[ 0 ][ "come_way" ] == "0" ){
					SweepstakeUser_table::addPointByPublishWay( $comeWayId, $this->sweepstakeId, $sweepstakeData[ "bonus_point" ] );
				}
			}
		}
		
		
		SweepstakeUser_table::registerEnter( $this->userId, $this->sweepstakeId, array("come_way" => $comeWayId, "ip" => GlobalHelper::getClientIp()) );
		
		SweepstakeStatistics_table::registerEnter( $this->sweepstakeId );
		
		$fbUserData = FbUser_table::getUser( $this->userId );
		mail( $fbUserData[ "email" ], ("welcome to " . $sweepstakeData[ "title" ]), $sweepstakeData[ "welcome_message" ], "Content-type: text/html; charset=UTF-8");
	}
	
	public function registerEmail( $sweepstakeId, $userId, $email ){
		$this->setCurrentState( self::$STATE__REGISTER_EMAIL );
		$this->userId = $userId;
		$this->sweepstakeId = $sweepstakeId;
		
		FbUser_table::updateData( $this->userId, array( "email" => $email ) );
	}
	
	public function registerTwitterPublish( $sweepstakeId, $userId, $wayId ){
		$this->setCurrentState( self::$STATE__REGISTER_TWITTER_PUBLISH );
		$this->userId = $userId;
		$this->sweepstakeId = $sweepstakeId;
		
		$pointPublishDate = UserPublish_table::getLastPointDate( $sweepstakeId, $userId, self::$publishType_twitterPublish );
		$pointPublish = false;
		if( (time() - $pointPublishDate) >= self::$publishPointInterval ){
			$pointPublish = true;
			$this->appPoint = 1;
			SweepstakeUser_table::addPoint( $this->sweepstakeId, $this->userId, $this->appPoint );
		}
		UserPublish_table::setPublish( $sweepstakeId, $userId, $wayId, self::$publishType_twitterPublish, "", $pointPublish );
		SweepstakeUser_table::registerPublish( $userId, $sweepstakeId );
		SweepstakeStatistics_table::registerPublish( $this->sweepstakeId );
	}
	
	public function registerFacebookPublish( $sweepstakeId, $userId, $wayId, $noPoint ){
		$this->setCurrentState( self::$STATE__REGISTER_FACEBOOK_PUBLISH );
		$this->userId = $userId;
		$this->sweepstakeId = $sweepstakeId;
		
		$pointPublish = false;
		if( $noPoint != "1" ){
			$pointPublishDate = UserPublish_table::getLastPointDate( $sweepstakeId, $userId, self::$publishType_facebookPublish );
			if( (time() - $pointPublishDate) >= self::$publishPointInterval ){
				$pointPublish = true;
				$this->appPoint = 1;
				SweepstakeUser_table::addPoint( $this->sweepstakeId, $this->userId, $this->appPoint );
			}
		}
		UserPublish_table::setPublish( $sweepstakeId, $userId, $wayId, self::$publishType_facebookPublish, "", $pointPublish );
		SweepstakeUser_table::registerPublish( $userId, $sweepstakeId );
		SweepstakeStatistics_table::registerPublish( $this->sweepstakeId );
	}
	
	public function registerFacebookInvite( $sweepstakeId, $userId, $wayId, $toUsersList ){
		$this->setCurrentState( self::$STATE__REGISTER_FACEBOOK_INVITE );
		$this->userId = $userId;
		$this->sweepstakeId = $sweepstakeId;
		
		if( count( $toUsersList ) > 0 ){
			for( $i = 0; $i < count( $toUsersList ); $i++ ){
				UserPublish_table::setPublish( $sweepstakeId, $userId, $wayId, self::$publishType_facebookInvite, $toUsersList[ $i ] );
			}
			
			SweepstakeUser_table::registerInvite( $userId, $sweepstakeId, count( $toUsersList ) );
			SweepstakeStatistics_table::registerInvite( $this->sweepstakeId, count( $toUsersList ) );
		}
	}
}