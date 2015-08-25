<?php
namespace framework\core\module;

abstract class FrameModModelParent {
	private $moduleId = null;
	private $moduleClassName = null;
	private $moduleDirName = null;
	private $actionName = null;
	private $currentState = null;
	private $errorMessage = null;
	
	public static $STATE__NO_ACTION = "no_action";
	public static $STATE__NO_PERMISSION = "no_permission";
	public static $STATE__ERROR = "error";
	
	
	public function getModuleId(){
		return $this->moduleId;
	}
	public function getModuleClassName(){
		return $this->moduleClassName;
	}
	public function getModuleDirName(){
		return $this->moduleDirName;
	}
	public function getActionName(){
		return $this->actionName;
	}
	
	
	public function __construct( $moduleId, $className, $dirName, $actionName ){
		$this->moduleId = $moduleId;
		$this->moduleClassName = $className;
		$this->moduleDirName = $dirName;
		$this->actionName = $actionName;
	}
	
	public function getCurrentState(){
		return $this->currentState;
	}
	
	protected function setCurrentState( $state ){
		$this->currentState = $state;
	}
	
	
	public function isNoActionState(){
		return (self::$STATE__NO_ACTION === $this->getCurrentState() ? true : false );
	}
	public function setNoAction(){
		$this->setCurrentState( self::$STATE__NO_ACTION );
	}
	
	
	public function isNotPermittedState(){
		return (self::$STATE__NO_PERMISSION === $this->getCurrentState() ? true : false );
	}
	public function setNotPermitted(){
		$this->setCurrentState( self::$STATE__NO_PERMISSION );
	}
	
	
	public function isErrorState(){
		return (self::$STATE__ERROR === $this->getCurrentState() ? true : false );
	}
	public function getErrorMessage(){
		return $this->errorMessage;
	}
	protected function setError( $message = "" ){
		$this->setCurrentState( self::$STATE__ERROR );
		$this->errorMessage = $message;
	}
}