var FrameModFbTab_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__SAVE = "saveEditAppTab";
	
	var sweepstakeId = null;
	var sweepstakeAppKey = null;
	
	var setApp = false;
	
	this.startWork = function( modId, sId, appKey ){
		_ParentObject = new FrameModParent( modId );
		sweepstakeId = sId;
		sweepstakeAppKey = appKey;
		
		document.getElementById( "frameModFbTab_login" ).onclick = checkAppKeySecret.bind( this );
	}
	
	
	function checkAppKeySecret( e ){
		if( strTrim( document.getElementById( "frameModFbTab_appKey" ).value ) == "" ){
			document.getElementById( "frameModFbTab_step1Error" ).style.color = "red";
			document.getElementById( "frameModFbTab_step1Error" ).innerHTML = "please fill App Key";
		}else
		if( strTrim( document.getElementById( "frameModFbTab_appSecret" ).value ) == "" ){
			document.getElementById( "frameModFbTab_step1Error" ).style.color = "red";
			document.getElementById( "frameModFbTab_step1Error" ).innerHTML = "please fill App Secret";
		}else{
			document.getElementById( "frameModFbTab_step1Error" ).style.color = "orange";
			document.getElementById( "frameModFbTab_step1Error" ).innerHTML = "loading...";
			
			if( setApp ){
				checkAppKeySecret_response.bind( this )( "" );
			}else{
				var url = "https://graph.facebook.com/oauth/access_token?client_id=" + document.getElementById( "frameModFbTab_appKey" ).value +  "&client_secret=" + document.getElementById( "frameModFbTab_appSecret" ).value +  "&grant_type=client_credentials";
				sendRequestViaAjax( url, "GET", checkAppKeySecret_response.bind( this ), "" );
			}
		}
	}
	
	function checkAppKeySecret_response( res ){
		var parts = res.split( "Error" );
		if( parts.length > 1 ){
			document.getElementById( "frameModFbTab_step1Error" ).style.color = "red";
			document.getElementById( "frameModFbTab_step1Error" ).innerHTML = "App Key or Secret is invalid";
		}else{
			setApp = true;
			document.getElementById( "frameModFbTab_appKey" ).readOnly = true;
			document.getElementById( "frameModFbTab_appSecret" ).readOnly = true;
			var self = this;
			
			window.fbAsyncInit = function() {
				FB.init({
					appId      : sweepstakeAppKey,
					cookie     : true,  // enable cookies to allow the server to access 
										// the session
					xfbml      : true,  // parse social plugins on this page
					version    : 'v2.1' // use version 2.1
				});
				
				FB.getLoginStatus(function(response) {
					if (response.status === 'connected') {
						userIsInsideApp.bind( self )();
					} else {
						FB.login(function(response) {
							if (response.authResponse) {
								userIsInsideApp.bind( self )();
							}else{
								document.getElementById( "frameModFbTab_step1Error" ).style.color = "red";
								document.getElementById( "frameModFbTab_step1Error" ).innerHTML = "Please confirm app";
							}
						}, {scope: 'email,public_profile,user_friends,user_birthday,manage_pages,user_location'});
					}
				});
			};

			// Load the SDK asynchronously
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

		}
	}
	
	function userIsInsideApp(){
		var self = this;
		
		FB.api('/me/accounts', function(response) {			
			document.getElementById( "frameModFbTab_step1" ).style.display = "none";
			document.getElementById( "frameModFbTab_step2" ).style.display = "block";
			
			var selectView = "<select id='frameModFbTab_pagesList' style='width: 300px;'>";
			for( var i = 0; i < response.data.length; i++ ){
				selectView += "<option value='" + response.data[ i ].id + "|" + response.data[ i ].access_token + "'>" + response.data[ i ].name + "</option>";
			}
			selectView += "</select>";
			
			document.getElementById( "frameModFbTab_pagesListParent" ).innerHTML = selectView;
			document.getElementById( "frameModFbTab_save" ).onclick = startSave.bind( self );
		});
	}
	
	function startSave( e ){
		if( strTrim( document.getElementById( "frameModFbTab_tabName" ).value ) == "" ){
			document.getElementById( "frameModFbTab_step2Error" ).style.color = "red";
			document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Please fill Tab Name";
		}else
		if( document.getElementById( "frameModFbTab_tabImage" ).files.length == 0 ){
			document.getElementById( "frameModFbTab_step2Error" ).style.color = "red";
			document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Please choose Tab Image";
		}else{
			document.getElementById( "frameModFbTab_step2Error" ).style.color = "orange";
			document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Loading...";
			
			var paramsArray = {};
			paramsArray[ "appKey" ] = document.getElementById( "frameModFbTab_appKey" ).value;
			paramsArray[ "appSecret" ] = document.getElementById( "frameModFbTab_appSecret" ).value;
			paramsArray[ "tabName" ] = document.getElementById( "frameModFbTab_tabName" ).value;
			paramsArray[ "sweepstakeId" ] = sweepstakeId;
			paramsArray[ "imageField" ] = "imageField";
						
			submitFormViaAjax( FrameworkFunc.getUrl( _ACTION__SAVE, _ParentObject.getModuleId() ), responseFromServer.bind( this ), paramsArray, document.getElementById( "frameModFbTab_tabImage" ) );
		}
	}
	
	function endSave( imgUrl ){
		imgUrl = "http:" + imgUrl;
		
		var parts = document.getElementById( "frameModFbTab_pagesList" ).value.split( "|" );
		
		FB.api(
			("/" + parts[ 0 ] + "/tabs"),
			"POST",
			{
				"app_id": document.getElementById( "frameModFbTab_appKey" ).value,
			//"custom_image": imgUrl,
				"access_token": parts[ 1 ]
			},
			function (response) {
				console.log( response );
				console.log( imgUrl );
				
				if( response.success ){
					FB.api(
						("/" + parts[ 0 ] + "/tabs/app_" + document.getElementById( "frameModFbTab_appKey" ).value),
						"POST",
						{
							//"app_id": document.getElementById( "frameModFbTab_appKey" ).value,
							"custom_image_url": imgUrl,
							"access_token": parts[ 1 ]
						},
						function (response) {
							console.log( response );
							
							if( response.success ){
								document.getElementById( "frameModFbTab_step2Error" ).style.color = "green";
								document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Save Success";
							}else{
								document.getElementById( "frameModFbTab_step2Error" ).style.color = "red";
								document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Error from facebook: " + response.error.message;
								console.log( response );
							}
						}
					);
					
					
					document.getElementById( "frameModFbTab_step2Error" ).style.color = "green";
					document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Save Success";
				}else{
					document.getElementById( "frameModFbTab_step2Error" ).style.color = "red";
					document.getElementById( "frameModFbTab_step2Error" ).innerHTML = "Error from facebook: " + response.error.message;
					console.log( response );
				}
			}
		);
	}
	
	function saveError( message ){
		document.getElementById( "frameModFbTab_step2Error" ).style.color = "red";
		document.getElementById( "frameModFbTab_step2Error" ).innerHTML = message;
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
		if( !_ParentObject.checkOneResponse( resObj, "frameModFbTab_step2Error" ) ){ // NO ERROR, NO PERRMISSION PROBLEM ... normal work
			if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
				if( resObj[ "moduleAction" ] == "saveSuccess" ){
					endSave.bind( this )( resObj[ "moduleActionParam" ] );
				}else
				if( resObj[ "moduleAction" ] == "saveError" ){
					saveError.bind( this )( resObj[ "moduleActionParam" ] );
				}
			}
		}
	}
}