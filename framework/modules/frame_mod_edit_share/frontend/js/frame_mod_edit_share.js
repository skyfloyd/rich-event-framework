var FrameModEditShare_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__FINISH_SAVE = "save";
	
	var idElement = null;
	var statusMessage = null;
	var dataStructure = null;
	
	var frameModEditFormViewParent = null;
	
	
	this.startWork = function( modId, idEl, finishButtton, statusMess, ds ){
		_ParentObject = new FrameModParent( modId );
		idElement = idEl;
		statusMessage = statusMess;
		dataStructure = ds;
		
		frameModEditFormViewParent = new FrameModEditFormViewParent();
		
		document.getElementById( finishButtton ).onclick = finish.bind( this );
	}
	
	function finish( e ){
		var returnParams = frameModEditFormViewParent.checkForm( dataStructure, statusMessage );
		console.log( dataStructure );
		if( returnParams !== false ){
			document.getElementById( statusMessage ).innerHTML = "saving process...";
			document.getElementById( statusMessage ).style.color = "orange";
			
			returnParams[ "textData" ][ "sweepstakeId" ] = document.getElementById( idElement ).value;
			
			var fileData = ( typeof returnParams[ "fileData" ] === 'undefined' || typeof returnParams[ "fileData" ][ "file" ] === 'undefined' ? null : returnParams[ "fileData" ][ "file" ] )
			submitFormViaAjax( FrameworkFunc.getUrl( _ACTION__FINISH_SAVE, _ParentObject.getModuleId() ), responseFromServer.bind( this ), returnParams[ "textData" ], fileData );
		}
	}
	
	function responseFromServer( data ){
		var resObj = JSON.parse( data );
		
		if( FrameworkFunc.checkResponse( resObj ) ){
			if( typeof resObj[ 0 ] !== 'undefined' ){
				for( var i = 0; i < resObj.length; i++ ){
					doResponse.bind( this )( resObj[ i ] );
				}
			}else{
				doResponse.bind( this )( resObj );
			}
		}
	}
	
	function doResponse( resObj ){
		if( !_ParentObject.checkOneResponse( resObj, statusMessage ) ){ // NO ERROR, NO PERRMISSION PROBLEM ... normal work
			if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
				if( resObj[ "moduleAction" ] == "saveSuccess" ){
					document.getElementById( statusMessage ).innerHTML = "saved successfully";
					document.getElementById( statusMessage ).style.color = "green";
				}else
				if( resObj[ "moduleAction" ] == "saveError" ){
					document.getElementById( statusMessage ).innerHTML = resObj[ "moduleActionParam" ];
					document.getElementById( statusMessage ).style.color = "red";
				}
			}
		}
	}
	
	this.getStringFromTime = function( dateObj ){
		var month = dateObj.getMonth() + 1;
		month = (month > 9 ? month : ("0" + month));
		var day = (dateObj.getDate() > 9 ? dateObj.getDate() : ("0" + dateObj.getDate()));
		var hour = dateObj.getHours() + 1;
		hour = (hour > 9 ? hour : ("0" + hour));
		var minute = dateObj.getMinutes();
		minute = (minute > 9 ? minute : ("0" + minute));
		
		return dateObj.getFullYear() + "/" + month + "/" + day + " " + hour + ":" + minute;
	}
	
	this.getTimeFromString = function( str ){
		var data = str.match(/\d+/g);
		return (new Date( data[ 0 ], data[ 1 ], data[ 2 ], data[ 3 ], data[ 4 ] ).getTime() / 1000);
	}
}