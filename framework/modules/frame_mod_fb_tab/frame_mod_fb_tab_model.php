<?php
class FrameModFbTab_Model extends FrameModModelParent {
		/*must be excluded to XML, to be configurable*/
	private $sweepstakeId = null;
	private $errorMessage = null;
	private $tabImgUrl = null;
	
	public static $STATE__START_EDIT = "start_edit";
	public static $STATE__SAVE_SUCCESS = "save_success";
	
	
	
	public function getSweepstakeId(){
		return $this->sweepstakeId;
	}
		
	public function getTabImgUrl(){
		return $this->tabImgUrl;
	}
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		parent::__construct( $moduleId, $className, $dirName, $actionName );
	}
	
	public function getSweepstakeData(){
		$data = Sweepstake_table::getSweepstakeById( $this->sweepstakeId );
		return $data;
	}
	
	public function startEdit( $seepstakeId ){
		$this->sweepstakeId = $seepstakeId;
		$this->setCurrentState( self::$STATE__START_EDIT );
	}
	
	public function edit( $seepstakeId, $imageField, $tabName, $appKey, $appSecret ){
		$this->sweepstakeId = $seepstakeId;
		Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "app_key" => $appKey, "app_secret" => $appSecret ) );
		
		$fileName = GlobalHelper::saveUploadedFile( $imageField, Config::getDataFileDir(), GlobalHelper::generateRandomString( 10 ) );
		if( is_null( $fileName ) ){
			$this->setError( "file upload problem" );
		}else{
			$sweepstakeData = $this->getSweepstakeData();
			
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id=" . $sweepstakeData[ "app_key" ] . "&client_secret=" . $sweepstakeData[ "app_secret" ] . "&grant_type=client_credentials";
			$app_access_token = @file_get_contents( $token_url );
			if( $app_access_token === false ){
				$this->setError( "Bad App key/secret" );
			}else{
				$sweepstakeUrl = SweepstakeManager::getEndSweepstakeBaseUrl() . $sweepstakeData[ "url" ] . "/";
				$parts = explode( "//", $sweepstakeUrl );
				$sweepstakeUrl = "http://" . $parts[ 1 ];
				$secureSweepstakeUrl = "https://" . $parts[ 1 ];
				
				$app_access_token = trim( $app_access_token );
				
				$postFields = 'page_tab_url=' . $sweepstakeUrl . '&secure_page_tab_url=' . $secureSweepstakeUrl . '&page_tab_default_name=' . $tabName . '&' . $app_access_token;
					
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, ("https://graph.facebook.com/" . $sweepstakeData[ "app_key" ]));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$res = curl_exec($ch);
				curl_close($ch);
				
				$this->setCurrentState( self::$STATE__SAVE_SUCCESS );
				$this->tabImgUrl = Config::getDataFileUrl() . $fileName;
				Sweepstake_table::updateSweepstake( $this->sweepstakeId, array( "share_tab_icon" => $this->tabImgUrl ) );
			}
		}
	}
}