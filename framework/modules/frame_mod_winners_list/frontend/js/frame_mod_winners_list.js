var FrameModWinnersList_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__REFRESH_LIST = "refreshWinnersList";
	var _ACTION__CHOOSE_WINNER = "chooseWinners";
	var _ACTION__REMOVE_WINNER = "removeWinners";
	var _ACTION__EMAIL_WINNER = "emailWinners";
	
	var parentElement = null;
	var sweepstakeId = null;
	var chooseButtonId = null;
	var chooseCountId = null;
	var currentEmailObjId = null;
	
	
	
	this.startWork = function( modId, sId, parentEl, cbId, ccId ){
		_ParentObject = new FrameModParent( modId );
		sweepstakeId = sId
		parentElement = parentEl;
		chooseButtonId = cbId;
		chooseCountId = ccId;
		
		if( chooseButtonId !== null ){
			document.getElementById( chooseButtonId ).onclick = chooseWinners.bind( this );
		}
		
		refreshList.bind( this )();
	}
	
	function refreshList(){
		var postParam = "sweepstakeId=" + sweepstakeId;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REFRESH_LIST, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
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
		if( !_ParentObject.checkOneResponse( resObj ) ){ // NO ERROR, NO PERRMISSION PROBLEM ... normal work
			if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
				if( resObj[ "moduleAction" ] == "winnersList" ){
					drawListView.bind( this )( resObj[ "moduleActionParam" ] );
				}else
				if( resObj[ "moduleAction" ] == "emailSent" ){
					drawEmailSent.bind( this )();
				}
			}
		}else{
			if( _ParentObject.getRequestAction() == _ACTION__EMAIL_WINNER ){
				activateEmailSend.bind( this )();
			}
		}
	}
	
	function activateEmailSend(){
		document.getElementById( currentEmailObjId ).className = "linkMe";
		currentEmailObjId = null;
	}
	function drawEmailSent(){
		activateEmailSend();
		alert( "email sent" );
	}
	
	function drawListView( data ){
		var view = "<table class='tableStyle1'>" + 
		"<thead><tr><th>Email</th><th>Entry Data</th><th>Facebook</th><th>Entry Date</th><th>Win Date</th><th style='width: 100px;'>Actions</th></tr></thead>";
		for( var i = 0; i < data.length; i++ ){
			var rowClass = "";
			if( i % 2 == 0 ){
				rowClass = "odd";
			}
			
			view += "<tr class='" + rowClass + "'>" + 
						"<td>" + data[ i ][ "email" ] + "</td>" + 
						"<td><table align='center'>" + 
							"<tr><td style='font-weight: bold;'>location:</td><td>" + data[ i ][ "location" ] + "</td></tr>" +
							"<tr><td style='font-weight: bold;'>birthday:</td><td>" + getStringFromTime( new Date( (data[ i ][ "birthday" ] * 1000) ) ) + "</td></tr>" +
							"<tr><td style='font-weight: bold;'>ip:</td><td>" + data[ i ][ "ip" ] + "</td></tr>" +
						"</table></td>" + 
						"<td><a href='http://www.facebook.com/" + data[ i ][ "fb_user_id" ] + "' target='_blank' >" + data[ i ][ "first_name" ] + " " + data[ i ][ "last_name" ] + "</a></td>" +  
						"<td>" + getStringFromTime( new Date( (data[ i ][ "last_enter_date" ] * 1000) ) ) + "</td>" + 
						"<td>" + getStringFromTime( new Date( (data[ i ][ "win_date" ] * 1000) ) ) + "</td>" + 
						"<td>" + 
							"<div class='linkMe' id='FrameModWinnersList__email__" + data[ i ][ "user_id" ] + "' name='" + data[ i ][ "user_id" ] + "' style='float:left; padding-right: 25px;' >email</div>" +
							"<div class='linkMe' id='FrameModWinnersList__remove__" + data[ i ][ "user_id" ] + "' name='" + data[ i ][ "user_id" ] + "' style='float:left;' >remove</div>" +
						"</td>" + 
					"</tr>";
		}
		view += "</table>";
		
		
		document.getElementById( parentElement ).innerHTML = view;
		
		for( var i = 0; i < data.length; i++ ){
			document.getElementById( ("FrameModWinnersList__email__" + data[ i ][ "user_id" ]) ).onclick = emailWinner.bind( this );
			document.getElementById( ("FrameModWinnersList__remove__" + data[ i ][ "user_id" ]) ).onclick = removeWinner.bind( this );
		}
	}
	

	function chooseWinners( e ){
		var postParam = "count=" + document.getElementById( chooseCountId ).value + "&sweepstakeId=" + sweepstakeId;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__CHOOSE_WINNER, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function emailWinner( e ){
		if( currentEmailObjId === null ){
			var target = e.target || e.srcElement;
			
			currentEmailObjId = target.id;
			document.getElementById( currentEmailObjId ).className = "linkMe_disable";
			
			var parts = target.id.split( "__email__" );
			var postParam = "userId=" + parts[ 1 ] + "&sweepstakeId=" + sweepstakeId;
			sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__EMAIL_WINNER, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
		}
	}
	
	function removeWinner( e ){
		var target = e.target || e.srcElement;
		var parts = target.id.split( "__remove__" );
		var postParam = "userId=" + parts[ 1 ] + "&sweepstakeId=" + sweepstakeId;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REMOVE_WINNER, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function getStringFromTime( dateObj ){
		var month = dateObj.getMonth() + 1;
		month = (month > 9 ? month : ("0" + month));
		var day = (dateObj.getDate() > 9 ? dateObj.getDate() : ("0" + dateObj.getDate()));
		
		return (day + "/" + month + "/" + dateObj.getFullYear());
	}
}