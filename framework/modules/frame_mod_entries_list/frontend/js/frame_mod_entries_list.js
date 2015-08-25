var FrameModEntriesList_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__REFRESH_LIST = "refreshList";
	
	var parentElement = null;
	var sweepstakeObjId = null;
	
	this.startWork = function( modId, parentEl, id ){
		_ParentObject = new FrameModParent( modId );
		parentElement = parentEl;
		sweepstakeObjId = id;
				
		refreshList.bind( this )();
	}
	
	function refreshList(){
		var postParam = "sweepstakeId=" + document.getElementById( sweepstakeObjId ).value;
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
				if( resObj[ "moduleAction" ] == "refershList" ){
					drawListView.bind( this )( resObj[ "moduleActionParam" ] );
					
				}
			}
		}
	}
	
	function drawListView( data ){
		var view = "<table class='tableStyle1'>" + 
		"<thead><tr><th>Email</th><th>Entry Data</th><th>Facebook</th><th>Date</th></tr></thead>";
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
					"</tr>";
		}
		view += "</table>";
		
		
		document.getElementById( parentElement ).innerHTML = view;
	}
	
	
	function getStringFromTime( dateObj ){
		var month = dateObj.getMonth() + 1;
		month = (month > 9 ? month : ("0" + month));
		var day = (dateObj.getDate() > 9 ? dateObj.getDate() : ("0" + dateObj.getDate()));
		
		return (day + "/" + month + "/" + dateObj.getFullYear());
	}
}