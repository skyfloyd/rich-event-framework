<?php
class SweepstakeManager{
	
	private static $endSweepstakeBaseDir = "s/";
	private static $sweepstakeAppKey = "1464671137140837";
	
	public static function getEndSweepstakeBaseUrl(){
		return Config::getBaseURL() . self::$endSweepstakeBaseDir;
	}
	private static function getEndSweepstakeBaseDir(){
		return Config::getBaseDir() . self::$endSweepstakeBaseDir;
	}
	public static function getSweepstakeAppKey(){
		return self::$sweepstakeAppKey;
	}
	
	public static function createNewSweepstake( $sweepstakeId ){
		$sweepstakeUrl = GlobalHelper::generateRandomString( 10 );
		
		mkdir( (self::getEndSweepstakeBaseDir() . $sweepstakeUrl) );
		file_put_contents( (self::getEndSweepstakeBaseDir() . $sweepstakeUrl . "/index.php"), self::getSweepstakeIndexContent( $sweepstakeId ) );
		
		return $sweepstakeUrl;
	}
	
	
	private static function getSweepstakeIndexContent( $sweepstakeId ){
		return '<?php
$_REQUEST[ "templateId" ] = "framework_template_app";
$_REQUEST[ "actionId" ] = "startApp";
$_REQUEST[ "sweepstakeId" ] = "' . $sweepstakeId . '";

include_once( dirname( dirname( dirname(__FILE__) ) ) . "/index.php" );';
	}
}