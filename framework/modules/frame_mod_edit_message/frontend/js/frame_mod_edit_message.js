var FrameModEditMessage_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__FINISH_SAVE = "saveMessage";
	
	var idElement = null;
	var statusMessage = null;
	var editor1 = null;
	var editor2 = null;
	var editor3 = null;
	var editor4 = null;
	
	
	this.startWork = function( modId, idEl, finishButtton, statusMess, e1, e2, e3, e4 ){
		_ParentObject = new FrameModParent( modId );
		idElement = idEl;
		statusMessage = statusMess;
		editor1 = e1;
		editor2 = e2;
		editor3 = e3;
		editor4 = e4;
		
		document.getElementById( finishButtton ).onclick = finish.bind( this );
	}
	
	function finish( e ){
		editor1.post();
		editor2.post();
		editor3.post();
		editor4.post();
		
		document.getElementById( statusMessage ).innerHTML = "saving process...";
		document.getElementById( statusMessage ).style.color = "orange";
		
		
		var postParam = "sweepstakeId=" + document.getElementById( idElement ).value + 
						"&before_start_message=" + encodeURIComponent( document.getElementById( 'frameModEditMessage_start' ).value ) + 
						"&after_end_message=" + encodeURIComponent( document.getElementById( 'frameModEditMessage_end' ).value ) + 
						"&welcome_message=" + encodeURIComponent( document.getElementById( 'frameModEditMessage_welcome' ).value ) + 
						"&winner_message=" + encodeURIComponent( document.getElementById( 'frameModEditMessage_winner' ).value );
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__FINISH_SAVE, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
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
				if( resObj[ "moduleAction" ] == "messageSaveSuccess" ){
					document.getElementById( statusMessage ).innerHTML = "saved successfully";
					document.getElementById( statusMessage ).style.color = "green";
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