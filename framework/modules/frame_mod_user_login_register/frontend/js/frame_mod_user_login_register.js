var FrameModUserLoginRegister_manager = function(){
	var _MODULE_ID = "user_login_register";
	
	var _ACTION__GET_VIEW = "get_view";
	var _ACTION__LOGIN = "loginRequest";
	var _ACTION__LOGOUT = "logoutRequest";
	
	var parentElement = null;
	
	this.startWork = function( parentEl ){
		parentElement = parentEl;
		
		getState.bind( this )();
	}
	
	function getState(){	
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__GET_VIEW, _MODULE_ID ), "POST", responseFromServer.bind( this ), "" );
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
		if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
			if( resObj[ "moduleAction" ] == "defaultView" ){
				drawDefaultView.bind( this )();
			}else
			if( resObj[ "moduleAction" ] == "showIncorrectLogin" ){
				showErrorMessage.bind( this )( resObj[ "moduleActionParam" ] );
			}else
			if( resObj[ "moduleAction" ] == "showLoginData" ){
				drawLoginData.bind( this )( resObj[ "moduleActionParam" ] );
			}
		}
	}
	
	function drawDefaultView(){
		var view = "<div class='loginBg'><table cellspacing='10' style='padding-top: 50px;'>" + 
						"<tr><td><div id='" + _MODULE_ID + "_errorMessage' style='color: red;' ></div></td></tr>" + 
						"<tr><td align='center'><input type='text' id='" + _MODULE_ID + "_username' placeholder='login' /></td></tr>" + 
						"<tr><td align='center'><input type='password' id='" + _MODULE_ID + "_password' placeholder='password' /></td></tr>" + 
						"<tr><td align='center'><input type='button' id='" + _MODULE_ID + "_loginButton' value='login' /></td></tr>" + 
					"</table></div>";
		document.getElementById( parentElement ).innerHTML = view;
		
		document.getElementById( (_MODULE_ID + "_loginButton") ).onclick = loginUser.bind( this );
	}
	
	function drawLoginData( data ){
		var view = "<table cellpadding='0' cellspacing='0' style='padding-top: 7px;'><tr><td style=\"font-family: arial; color: #ffffff; font-size: 14px;\">Login User: <b>" + data[ "login" ] + "</b></td></tr><tr><td><div id='" + _MODULE_ID + "_logoutButton' style='cursor: pointer; color: #ff5555; text-align: center; font-family: arial; font-size: 16px;'>logout</div></td></tr></table>";
		document.getElementById( parentElement ).innerHTML = view;
		
		document.getElementById( (_MODULE_ID + "_logoutButton") ).onclick = logoutUser.bind( this );
	}
	
	function loginUser( e ){		
		if( strTrim( document.getElementById( (_MODULE_ID + "_username") ).value ) == "" ){
			showErrorMessage( "please fill username" );
		}else
		if( strTrim( document.getElementById( (_MODULE_ID + "_password") ).value ) == "" ){
			showErrorMessage( "please fill password" );
		}else{
			var postParams = "userLogin=" + strTrim( document.getElementById((_MODULE_ID + "_username")).value ) + "&userPass=" + strTrim( document.getElementById((_MODULE_ID + "_password")).value );
			sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__LOGIN, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParams );
		}
	}
	
	function logoutUser( e ){
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__LOGOUT, _MODULE_ID ), "POST", responseFromServer.bind( this ), "" );
	}
	
	function showErrorMessage( message ){
		document.getElementById( (_MODULE_ID + "_errorMessage") ).innerHTML = message;
	}
}