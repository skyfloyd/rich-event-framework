<?php
namespace framework\core;

class FrameworkResponse {
	public static $STATUS__READY = 0;
	public static $STATUS__ERROR = 1;
	public static $STATUS__CHANGE_TEMPLATE = 2;
	public static $STATUS__CHANGE_MODULE = 3;
	
	
	private $status = null;
	private $statusMessage = null;
	private $data = null;
	private $html = null;
	private $script = null;
	private $import = null;
	private $json = null;
	
	public function setStatus( $status ){
		$this->status = $status;
	}
	public function getStatus(){
		return $this->status;
	}
	
	public function setStatusMessage( $statusMessage ){
		$this->statusMessage = $statusMessage;
	}
	public function getStatusMessage(){
		return $this->statusMessage;
	}
	
	public function setData( $data ){
		$this->data = $data;
	}
	public function getData(){
		return $this->data;
	}
	
	public function setHtml( $html ){
		$this->html = $html;
	}
	public function getHtml(){
		return $this->html;
	}
	
	public function setScript( $script ){
		$this->script = $script;
	}
	public function getScript(){
		return $this->script;
	}
	
	public function setImport( $import ){
		$this->import = $import;
	}
	public function getImport(){
		return $this->import;
	}
	
	public function setJson( $json ){
		$this->json = $json;
	}
	public function getJson(){
		return $this->json;
	}
	
	public function changeTemplate( $templateIdAndParams ){
		$json = array( "frameworkAction" => "changeTemplate", "frameworkActionParam" => $templateIdAndParams );
		$this->setJson( $json );
	}
}