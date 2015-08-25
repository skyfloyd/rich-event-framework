var FrameModParent = function( modId ){
	var _MODULE_ID = modId;
	
	var separater = "__";
	var responsePostfix_Error = "ERROR";
	var stateError = "error";
	var responsePostfix_NoPermission = "NO_PERMISSION";
	var stateNoPermission = "noPermission";
	
	var requestAction = null;
	var currentState = null;
	var stateMessage = null;
	
	this.getModuleId = function(){
		return _MODULE_ID;
	}
	
	this.getRequestAction = function(){
		return requestAction;
	}
	
	this.getStateMessage = function(){
		return stateMessage;
	}
	
	this.isErrorState = function(){
		return (stateError === currentState ? true : false );
	}
	this.isNotPermittedState = function(){
		return (stateNoPermission === currentState ? true : false );
	}
	
	this.checkOneResponse = function( resObj, messageObjId ){
		if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
			var parts = resObj[ "moduleAction" ].split( separater );
			
			// karucvacq@ hetevyalna _MODULE_ID __ requestAction __ responseName
			if( parts.length == 3 && parts[ 0 ] == _MODULE_ID ){
				var moduleId = parts[ 0 ];
				requestAction = parts[ 1 ];
				responseName = parts[ 2 ];
				
				if( responseName == responsePostfix_Error ){
					currentState = stateError;
					stateMessage = resObj[ "moduleActionParam" ];
					
					if( typeof messageObjId === 'undefined' ){
						alert( resObj[ "moduleActionParam" ] );
					}else
					if( messageObjId === false ){
					}else{
						document.getElementById( messageObjId ).style.color = "red";
						document.getElementById( messageObjId ).innerHTML = stateMessage;
					}
					return true;
				}else
				if( responseName == responsePostfix_NoPermission ){
					currentState = stateError;
					stateMessage = resObj[ "moduleActionParam" ];
					
					if( typeof messageObjId === 'undefined' ){
						alert( resObj[ "moduleActionParam" ] );
					}else
					if( messageObjId === false ){
					}else{
						document.getElementById( messageObjId ).style.color = "slateblue";
						document.getElementById( messageObjId ).innerHTML = stateMessage;
					}
					return true;
				}
			}
		}
		
		return false;
	}
}