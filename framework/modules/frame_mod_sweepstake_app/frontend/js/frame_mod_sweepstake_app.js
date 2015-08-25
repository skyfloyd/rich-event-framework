var FrameModSweepstakeApp_manager = function(){
	var _MODULE_ID = null;
	
	var _ACTION__REGISTER_VIEW = "registerView";
	var _ACTION__REGISTER_JOIN = "registerEnter";
	var _ACTION__REGISTER_EMAIL = "registerEmail";
	var _ACTION__REGISTER_INVITE_PUBLISH = "registerInvitePublish";
	var _ACTION__REGISTER_FACEBOOK_PUBLISH = "registerFacebookPublish";
	var _ACTION__REGISTER_TWITTER_PUBLISH = "registerTwitterPublish";
	
	var sweestakeId = null;
	var appKey = null;
	var statusMessage = null;
	var contentParentId = null;
	var headerParentId = null;
	var sweepstakeData = null;
	var sweepstakeUserData = null;
	var sweepstakeBaseUrl = null;
	var userComeWayId = null;
	var serverTime = null;
	var addPoint = 0;
	var facebookPublishWayId = 0;
	
	var fbUserId = 0;
	var connectedStatus = false;
	var firstName = null;
	var userEmail = null;
	
	this.waitFunc = null;
	
	this.startWork = function( modId, sId, aKey, sData, sbUrl, ucwId, serverT, hpId, cpId ){
		_MODULE_ID = modId;
		sweestakeId = sId;
		appKey = aKey;
		headerParentId = hpId;
		contentParentId = cpId;
		sweepstakeData = sData;
		sweepstakeBaseUrl = sbUrl;
		userComeWayId = ucwId;
		//sweepstakeUserData = suData;
		serverTime = serverT;
		
		document.getElementById( headerParentId ).innerHTML = sweepstakeData[ "title" ];
		
		if( serverT < sweepstakeData[ "start_date" ] ){
			document.getElementById( contentParentId ).innerHTML = sweepstakeData[ "before_start_message" ];
			registerView.bind( this )();
		}else
		if( serverT > sweepstakeData[ "end_date" ] ){
			document.getElementById( contentParentId ).innerHTML = sweepstakeData[ "after_end_message" ];
			registerView.bind( this )();
		}else{
			fbSetup.bind( this )();
		}

		//mi hat veranayel et vieweri uxarkman texer@
	}
	
	function fbSetup(){
		var self = this;
		
		window.fbAsyncInit = function() {
			FB.init({
				appId      : appKey,
				cookie     : true,  // enable cookies to allow the server to access 
							// the session
				xfbml      : true,  // parse social plugins on this page
				version    : 'v2.1' // use version 2.1
			});

			FB.getLoginStatus(function(response) {
				//statusChangeCallback(response);
				if( response [ "status" ] == "connected" ){
					connectedStatus = true;
					fbUserId = response [ "authResponse" ][ "userID" ]
					self.waitFunc = fbSetup_authorisation.bind( self );
					
					console.log( response );
					FB.api('/me', function(response) {
						registerView.bind( self )( response );
					});
				}else{
					connectedStatus = false;
					drawJoinPage.bind( self )();
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
	
	function fbSetup_authorisation(){
		this.waitFunc = null;
		
		if( sweepstakeUserData[ "birthday" ] == "0" || sweepstakeData[ "min_age" ] == "0" || (serverTime - sweepstakeUserData[ "birthday" ]) > (sweepstakeData[ "min_age" ] * 365 * 24 * 60 * 60) ){
			if( sweepstakeUserData[ "enter" ] == "0" ){
				drawJoinPage.bind( this )();
			}else
			if( sweepstakeData[ "enter_once_type" ] == "0" ){
				drawEndPage.bind( this )();
			}else
			if( (serverTime - sweepstakeUserData[ "last_enter_date" ]) < (60 * 60 * 24) ){
				drawEndPage.bind( this )();
				document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = "you can join again after " + this.getRoundTimeStringFromTime( ((60 * 60 * 24) - (serverTime - sweepstakeUserData[ "last_enter_date" ])) );
			}else{
				drawJoinPage.bind( this )();
			}
		}else{
			drawJoinPage.bind( this )();
			document.getElementById( "FrameModSweepstakeApp_join" ).style.display = "none";
			document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = sweepstakeData[ "restriction_text" ];
		}
	}
	
	function joinAction( e ){
		if( connectedStatus ){
			registerJoin.bind( this )();
		}else{
			var self = this;
			
			FB.login(function(response) {
				if (response.authResponse) {
					FB.api('/me', function(response) {
						console.log( response );
						console.log( response.email );
						fbUserId = response.id;
						
						if( typeof response.birthday == "undefined" || sweepstakeData[ "min_age" ] == "0" || (serverTime - self.getTimeFromString( response.birthday, true )) > (sweepstakeData[ "min_age" ] * 365 * 24 * 60 * 60) ){
							registerView.bind( self )( response );
							drawEmailConfirmPage.bind( self )( response.email, response.first_name );
						}else{
							registerView.bind( self )( response );
							drawJoinPage.bind( self )();
							document.getElementById( "FrameModSweepstakeApp_join" ).style.display = "none";
							document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = sweepstakeData[ "restriction_text" ];
						}

					});
				} else {
					registerView.bind( self )();
					document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = "to join you need to accept app first";
				}
			}, {scope: 'email,public_profile,user_friends,user_birthday,user_location'}); //user_location
		}
	}
	
	
	function publishEnter(){
		var wayId = GlobalLib.randomString( 10 );
		var url = "https:" + sweepstakeBaseUrl + sweepstakeData[ "url" ] + "?wi=" + wayId;
		var img = "http:" + sweepstakeData[ "share_image" ];
		addPoint = 1;
		facebookPublishWayId = wayId;
		streamPublish( (sweepstakeUserData[ "first_name" ] + " just joined to " + sweepstakeData[ "share_title" ]), "", sweepstakeData[ "share_desc" ], url, img, "", -1 );
	}
	
	function facebookPublish( e ){
		var wayId = GlobalLib.randomString( 10 );
		var url = "https:" + sweepstakeBaseUrl + sweepstakeData[ "url" ] + "?wi=" + wayId;
		var img = "http:" + sweepstakeData[ "share_image" ];
		addPoint = 0;
		facebookPublishWayId = wayId;
		streamPublish( sweepstakeData[ "share_title" ], "", sweepstakeData[ "share_desc" ], url, img, "", -1 );
	}
	function facebookInvite( e ){
		FB.ui({method: 'apprequests',
			message: sweepstakeData[ "share_ivite" ]
		}, function(response){
			console.log( response );
			if( typeof response.request != "undefined" && typeof response.to != "undefined" ){
				registerInvitePublish( response.request, response.to );
			}
		});
	}
	function twitterPublish( e ){
		var wayId = GlobalLib.randomString( 10 );
		var url = "https:" + sweepstakeBaseUrl + sweepstakeData[ "url" ] + "?wi=" + wayId;
		window.open( 'https://twitter.com/share?text=' + encodeURIComponent( sweepstakeData[ "share_twitter" ] ) + '&url=false', 'Tweet', 'location=100,status=0,width=600,height=350' );
		
		registerTwitterPublish( wayId );
	}
	
	function drawJoinPage(){
		var view = "<div style='padding-bottom: 20px;'>" + sweepstakeData[ "desc" ] + "</div><div></div><div id='FrameModSweepstakeApp_controllers'><div class='button' id='FrameModSweepstakeApp_join'><span>Join now!</span></div></div><div></div><div id='FrameModSweepstakeApp_message' style='color: red; padding-top: 20px;'></div>";
		
		document.getElementById( contentParentId ).innerHTML = view;
		document.getElementById( "FrameModSweepstakeApp_join" ).onclick = joinAction.bind( this );
	}
	
	function drawEndPage( e ){
		var point = (sweepstakeUserData[ "point" ] == "0" ? 1 : (sweepstakeUserData[ "point" ]/1) );
		var view = "<div style='padding-bottom: 10px; font-weight: bold;'>Hi " + sweepstakeUserData[ "first_name" ] + ", you are entered to win! You have <span id='FrameModSweepstakeApp_currentPoint'>" + point + "</span> entry</div><div style='padding-bottom: 20px;'>Get " + sweepstakeData[ "bonus_point" ] + " bonus entry every time a friend enters!</div><div></div>" + 
"<div class='button' id='FrameModSweepstakeApp_facebookPublish' style='margin-right: 10px;'><span>Share on Facebook</span></div><div class='button' id='FrameModSweepstakeApp_twitterPublish'><span>Share on Twitter</span></div><div class='button' id='FrameModSweepstakeApp_facebookInvite' style='margin-top: 10px;'><span>Invite</span></div><div></div>" +
"<div id='FrameModSweepstakeApp_message' style='color: red; padding-top: 20px;'></div>";
		
		document.getElementById( contentParentId ).innerHTML = view;
		document.getElementById( "FrameModSweepstakeApp_facebookPublish" ).onclick = facebookPublish.bind( this );
		document.getElementById( "FrameModSweepstakeApp_facebookInvite" ).onclick = facebookInvite.bind( this );
		document.getElementById( "FrameModSweepstakeApp_twitterPublish" ).onclick = twitterPublish.bind( this );
	}
	
	function drawEmailConfirmPage( email, name ){
		if( typeof email === "undefined" || email === null || email === null ){
			var view = "<div style='padding-bottom: 10px;'>Welcome " + name + ". Please provide your email.</div><input type='text' id='FrameModSweepstakeApp_newEmail' class='required email size_full text' /><div style='padding-bottom: 20px;'></div><div></div><div id='FrameModSweepstakeApp_controllers'><div class='button' id='FrameModSweepstakeApp_updateEmail' style='margin-right: 10px;'><span>update</span></div></div><div></div><div id='FrameModSweepstakeApp_message' style='color: red; padding-top: 20px;'></div>";
			
			document.getElementById( contentParentId ).innerHTML = view;
			document.getElementById( "FrameModSweepstakeApp_updateEmail" ).onclick = updateEmail.bind( this );
		}else{
			var view = "<div>Welcome " + name + ". Your current email is <b>" + email + "</b>.</div><div style='padding-bottom: 10px;'>If you use another one please upadate it.</div><input type='text' id='FrameModSweepstakeApp_newEmail' class='required email size_full text' /><div style='padding-bottom: 20px;'></div><div></div><div id='FrameModSweepstakeApp_controllers'><div class='button' id='FrameModSweepstakeApp_updateEmail' style='margin-right: 10px;'><span>update</span></div><div class='button' id='FrameModSweepstakeApp_skipUpdateEmail'><span>Skip</span></div></div><div></div><div id='FrameModSweepstakeApp_message' style='color: red; padding-top: 20px;'></div>";

			document.getElementById( contentParentId ).innerHTML = view;
			document.getElementById( "FrameModSweepstakeApp_updateEmail" ).onclick = updateEmail.bind( this );
			document.getElementById( "FrameModSweepstakeApp_skipUpdateEmail" ).onclick = skipUpdateEmail.bind( this );
		}
	}
	
	
	function skipUpdateEmail( e ){
		registerJoin.bind( this )();
	}
	function updateEmail( e ){
		if( GlobalLib.validateEmail( document.getElementById( "FrameModSweepstakeApp_newEmail" ).value ) ){
			userEmail = document.getElementById( "FrameModSweepstakeApp_newEmail" ).value;
			//registerEmail( document.getElementById( "FrameModSweepstakeApp_newEmail" ).value );
			registerJoin.bind( this )();
		}else{
			document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = "please type valid email";
		}
	}
	
	function registerView( response ){
		var postParam = "sweepstakeId=" + sweestakeId + "&fbUserId=" + fbUserId + "&userComeWayId=" + userComeWayId + getUserParamsFromFBresponse.bind( this )( response );
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_VIEW, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function registerJoin(){
		document.getElementById( "FrameModSweepstakeApp_controllers" ).style.display = "none";
		document.getElementById( "FrameModSweepstakeApp_message" ).innerHTML = "Loading...";
		
		var self = this;
		FB.api('/me', function(response) {
			console.log(response);
			var postParam = "sweepstakeId=" + sweestakeId + "&fbUserId=" + fbUserId + "&userComeWayId=" + userComeWayId + getUserParamsFromFBresponse.bind( self )( response );
			
			FB.api('/me/friends', function(response) {
				console.log(response);
				postParam += "&friends=" + JSON.stringify( response.data );
				sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_JOIN, _MODULE_ID ), "POST", responseFromServer.bind( self ), postParam );
			});
		});
	}
	
	function registerEmail( email ){
		var postParam = "sweepstakeId=" + sweestakeId + "&userId=" + sweepstakeUserData[ "user_id" ] + "&email=" + email;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_EMAIL, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function registerInvitePublish( requestId, toUsers ){
		if( toUsers.length > 0 ){
			var postParam = "sweepstakeId=" + sweestakeId + "&userId=" + sweepstakeUserData[ "user_id" ] + "&wayId=" + requestId + "&toUsers=" + JSON.stringify( toUsers );
			sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_INVITE_PUBLISH, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParam );
		}
	}
	
	function registerFacebookPublish( wayId, noPoint ){
		if( typeof noPoint == "undefined" ){
			noPoint = "0";
		}
		var postParam = "sweepstakeId=" + sweestakeId + "&userId=" + sweepstakeUserData[ "user_id" ] + "&wayId=" + wayId + "&noPoint=" + noPoint;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_FACEBOOK_PUBLISH, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function registerTwitterPublish( wayId ){
		var postParam = "sweepstakeId=" + sweestakeId + "&userId=" + sweepstakeUserData[ "user_id" ] + "&wayId=" + wayId;
		sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REGISTER_TWITTER_PUBLISH, _MODULE_ID ), "POST", responseFromServer.bind( this ), postParam );
	}
	
	function getUserParamsFromFBresponse( response ){
		postParam = "";
		
		if( typeof response != "undefined" ){
			if( userEmail !== null ){
				postParam += "&email=" + userEmail;
			}else
			if( typeof response.email != "undefined" ){
				postParam += "&email=" + response.email;
			}
			if( typeof response.first_name != "undefined" ){
				postParam += "&first_name=" + response.first_name;
			}
			if( typeof response.last_name != "undefined" ){
				postParam += "&last_name=" + response.last_name;
			}
			if( typeof response.gender != "undefined" ){
				postParam += "&gender=" + (response.gender == "female" ? 0 : 1);
			}
			if( typeof response.birthday != "undefined" ){
				postParam += "&birthday=" + this.getTimeFromString( response.birthday, true );
			}
			if( typeof response.location != "undefined" ){
				postParam += "&location=" + response.location.name;
			}
		}
		
		return postParam;
	}
	
	function addPointFromServer( point ){
		if( (point / 1) > 0 ){
			document.getElementById( "FrameModSweepstakeApp_currentPoint" ).innerHTML = ((document.getElementById( "FrameModSweepstakeApp_currentPoint" ).innerHTML / 1) + (point / 1));
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
		if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
			if( resObj[ "moduleAction" ] == "afterRegisterView" ){
				sweepstakeUserData = resObj[ "moduleActionParam" ];
				if( this.waitFunc != null ){
					this.waitFunc();
				}
			}else
			if( resObj[ "moduleAction" ] == "afterRegisterEnter" ){
				sweepstakeUserData = resObj[ "moduleActionParam" ];
				if( this.waitFunc != null ){
					this.waitFunc();
				}
				drawEndPage.bind( this )();
				
				if( sweepstakeData[ "publish_enter" ] == "1" ){
					publishEnter.bind( this )();
				}
			}else
			if( resObj[ "moduleAction" ] == "appPoint" ){
				addPointFromServer.bind( this )( resObj[ "moduleActionParam" ] );
			}
		}
	}
	
	function streamPublish( name, caption, description, refUrl, imgURL, userPrompt, targetId ){
		var pubObj = {
				method: 'stream.publish',
				message: '',
				
				attachment: {
					name: name,
					caption: caption,
					description: (description),
					href: refUrl,
					media:[{type:'image', src: imgURL, href: refUrl}]
				},
				user_prompt_message: userPrompt
			};
		if( targetId != -1 ){
			pubObj.target_id = targetId;
		}
		
		FB.ui(
			pubObj,
			function(response){ 
				console.log(response);
				if( typeof response !== "undefined" && response !== null ){
					registerFacebookPublish.bind( this )( facebookPublishWayId, addPoint );
				}
			}
		);
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
	
	this.getTimeFromString = function( str, revOrder ){
		if( typeof revOrder === 'undefined' ){
			revOrder = false;
		}
		var data = str.match(/\d+/g);
		if( revOrder ){
			data[ 0 ] = data[ 0 ] - 1;
			return (new Date( data[ 2 ], data[ 0 ], data[ 1 ] ).getTime() / 1000);
		}else{
			data[ 1 ] = data[ 1 ] - 1;
			return (new Date( data[ 0 ], data[ 1 ], data[ 2 ] ).getTime() / 1000);
		}
	}
	
	this.getRoundTimeStringFromTime = function( seconds ){
		if( (seconds / (60 * 60)) > 0 ){
			return Math.round( (seconds / (60 * 60)) ) + " hour" + (Math.round( (seconds / (60 * 60)) ) > 1 ? "s" : "");
		}else
		if( (seconds / 60) > 0 ){
			return Math.round( (seconds / 60) ) + " minute" + (Math.round( (seconds / 60) ) > 1 ? "s" : "");
		}else{
			return Math.round( seconds ) + " second" + (Math.round( seconds ) > 1 ? "s" : "");
		}
	}
}