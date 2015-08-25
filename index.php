<?php
session_start();

spl_autoload_register(function ($class) {
	require_once __DIR__ . '/' . $class . '.php';
});

use framework\FrameworkController;
use lib\GlobalHelper;


$responseForUser = FrameworkController::requestFromUser();

if( !GlobalHelper::isRequestAjax() ){
?>
	<meta charset="UTF-8">
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/jquery-2.1.1.min.js'></SCRIPT>
	<link rel="stylesheet" href="<?=\Config::getBaseURL()?>frontend/lib/bootstrap-3.2.0-dist/css/bootstrap.css">
	<script src="<?=\Config::getBaseURL()?>frontend/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
	
	<link rel='stylesheet' href='<?=\Config::getBaseURL()?>frontend/css/main.css' type='text/css' />
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/multiapplication.js'></SCRIPT>
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/lib.js'></SCRIPT>
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/ajax.js'></SCRIPT>
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/framework/core/core.js'></SCRIPT>
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/framework/core/module/frame_mod_parent.js'></SCRIPT>
	<script src='//ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js'></script>
	<SCRIPT LANGUAGE='Javascript' SRC='<?=\Config::getBaseURL()?>frontend/js/framework/core/angular_core.js'></SCRIPT>
<?php
}

echo( $responseForUser );