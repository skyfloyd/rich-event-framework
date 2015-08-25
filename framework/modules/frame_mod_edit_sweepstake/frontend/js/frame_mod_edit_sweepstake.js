var FrameModEditSweepstake_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__FINISH_SAVE = "saveSweepstake";
	
	var idElement = null;
	var statusMessage = null;
	var descriptionObj = null;
	
	this.startWork = function( modId, idEl, finishButtton, statusMess, deO ){
		_ParentObject = new FrameModParent( modId );
		idElement = idEl;
		statusMessage = statusMess;
		descriptionObj = deO;
		
		document.getElementById( finishButtton ).onclick = finish.bind( this );
	}
	
	function finish( e ){
		descriptionObj.post();
		
		document.getElementById( statusMessage ).innerHTML = "";
		document.getElementById( statusMessage ).style.color = "red";
		
		if( strTrim( document.getElementById( 'frameModEditSweepstake_title' ).value ) == "" ){
			document.getElementById( statusMessage ).innerHTML = "Please fill Title";
		}else
		if( strTrim( document.getElementById( 'frameModEditSweepstake_desc' ).value ) == "" ){
			document.getElementById( statusMessage ).innerHTML = "Please fill Description";
		}else
		if( FrameModEditSweepstake_manager.getTimeFromString( document.getElementById( 'frameModEditSweepstake_startDate' ).value ) > FrameModEditSweepstake_manager.getTimeFromString( document.getElementById( 'frameModEditSweepstake_endDate' ).value ) ){
			document.getElementById( statusMessage ).innerHTML = "Start Date can not be higher then End Date";
		}else
		if( isNaN( document.getElementById( 'frameModEditSweepstake_bonusPoint' ).value ) || (document.getElementById( 'frameModEditSweepstake_bonusPoint' ).value / 1) < 0 ){
			document.getElementById( statusMessage ).innerHTML = "Award point must be numeric and higher then 0";
		}else{
			document.getElementById( statusMessage ).innerHTML = "saving process...";
			document.getElementById( statusMessage ).style.color = "orange";
			
			
			var postParam = "sweepstakeId=" + document.getElementById( idElement ).value + 
							"&title=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_title' ).value ) + 
							"&desc=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_desc' ).value ) + 
							"&restriction_text=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_restricText' ).value ) + 
							"&start_date=" + FrameModEditSweepstake_manager.getTimeFromString( document.getElementById( 'frameModEditSweepstake_startDate' ).value ) + 
							"&end_date=" + FrameModEditSweepstake_manager.getTimeFromString( document.getElementById( 'frameModEditSweepstake_endDate' ).value ) + 
							"&enter_once_type=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_enterOnceType' ).value ) + 
							"&bonus_point=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_bonusPoint' ).value ) + 
							"&bonus_enter_type=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_bonusEnterType' ).value ) + 
							"&publish_enter=" + (document.getElementById( 'frameModEditSweepstake_publishEnter' ).checked ? "1" : "0") + 
							"&min_age=" + encodeURIComponent( document.getElementById( 'frameModEditSweepstake_minAge' ).value );
			sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__FINISH_SAVE, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
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
				if( resObj[ "moduleAction" ] == "sweepstakeSaveSuccess" ){
					document.getElementById( statusMessage ).innerHTML = "saved successfully";
					document.getElementById( statusMessage ).style.color = "green";
				}
			}
		}
	}
}

FrameModEditSweepstake_manager.getStringFromTime = function( dateObj ){
	var month = dateObj.getMonth() + 1;
	month = (month > 9 ? month : ("0" + month));
	var day = (dateObj.getDate() > 9 ? dateObj.getDate() : ("0" + dateObj.getDate()));
	var hour = dateObj.getHours() + 1;
	hour = (hour > 9 ? hour : ("0" + hour));
	var minute = dateObj.getMinutes();
	minute = (minute > 9 ? minute : ("0" + minute));
	
	return dateObj.getFullYear() + "/" + month + "/" + day + " " + hour + ":" + minute;
}

FrameModEditSweepstake_manager.getTimeFromString = function( str ){
	var data = str.match(/\d+/g);
	data[ 1 ] = data[ 1 ] - 1;
	data[ 3 ] = data[ 3 ] - 1;
	data[ 4 ] = data[ 4 ] - 1;
	return (new Date( data[ 0 ], data[ 1 ], data[ 2 ], data[ 3 ], data[ 4 ] ).getTime() / 1000);
}