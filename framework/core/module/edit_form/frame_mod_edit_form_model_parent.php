<?php
abstract class FrameModEditFormModelParent extends FrameModModelParent {
	private $moduleId = null;
	private $moduleClassName = null;
	private $moduleDirName = null;
	private $currentState = null;
	
	
	public function getModuleId(){
		return $this->moduleId;
	}
	public function getModuleClassName(){
		return $this->moduleClassName;
	}
	public function getModuleDirName(){
		return $this->moduleDirName;
	}
	
	public function __construct( $moduleId, $className, $dirName ){
		$this->moduleId = $moduleId;
		$this->moduleClassName = $className;
		$this->moduleDirName = $dirName;
	}
	
	public function getCurrentState(){
		return $this->currentState;
	}
	
	protected function setCurrentState( $state ){
		$this->currentState = $state;
	}
}