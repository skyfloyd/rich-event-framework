var destMod_ModSeparator = "~"; 
var destMod_ActSeparator = ">"; 
var respModAct_DataSeparator = "^";




angular.module('myFrameworkCoreModule', [])
	.factory('myFrameworkCore', function($rootScope, $http) {
	    var SharedService = function(){
	    	var self = this;
	    	var resObj = {};
	    	this.listenerMethodPrefix = "modListener_";
	    	var effectExRootDir = location.href + "/";
	    
		    var responseFromServer = function( res ){
		    	if( Array.isArray( res ) ){
		    		for( var i = 0; i < res.length; i++ ){
		    			var result = checkOneResponse.bind( self )( res[ i ] );
		    			if( !result ){
		    				return result;
		    			}
		    		}
		    	}else{
		    		return checkOneResponse.bind( self )( res );
		    	}
		    	
		    	return true;
		    }
	    
		    var checkOneResponse = function( res ){
		    	console.log( res );
		    	if( res[ "frameworkAction" ] != undefined ){
		    		if( res[ "frameworkAction" ] == "changeTemplate" ){
		    			GlobalLib.redirectByPost( effectExRootDir, ("templateId=" + res[ "frameworkActionParam" ]) );
		    			//document.location = effectExRootDir + "?templateId=" + res[ "frameworkActionParam" ];
		    			return false;
		    		}
		    	}else{
		    		 $rootScope.$broadcast( (this.listenerMethodPrefix + res[ "moduleId" ] + "_" + res[ "moduleAction" ]), res[ "moduleActionParam" ] );
		    		 //$rootScope.$broadcast( (sharedService.listenerMethodPrefix + "_" + res[ "moduleAction" ]), res[ "moduleActionParam" ] );
		    	}
		    	
		    	return true;
		    }
	    
		    this.sendAjaxRequest = function( actionId, moduleId, templateId, getParams, postParam, ajaxCall, isPOST ){
		    	templateId = ((typeof templateId !== 'undefined' && templateId !== null) ? templateId : _CURRENT_TEMPLATE_ID);
		    	getParams = ((typeof getParams !== 'undefined' && getParams !== null) ? ("&" + getParams) : "");
		    	postParam = ((typeof postParam !== 'undefined' && postParam !== null) ? postParam : "");
		    	ajaxCall = ((typeof ajaxCall !== 'undefined' && ajaxCall !== null) ? ajaxCall : true);
		    	isPOST = ((typeof isPOST !== 'undefined' && isPOST !== null) ? isPOST : true);
		    	
		    	var url = effectExRootDir + "?actionId=" + actionId + "&moduleId=" + moduleId + "&templateId=" + templateId + (ajaxCall ? "&ajaxCall=1" : "") + getParams;
		    	
		    	var reqParams = {url: url, headers: {'Content-Type': 'application/x-www-form-urlencoded'}, method: 'GET'};
		    	if( isPOST ){
		    		reqParams.method = 'POST';
		    		reqParams.data = postParam;
		    	}
		    	
		    	$http( reqParams ).success(function(data, status, headers, config) { responseFromServer.bind( self )( data ); });
		    	
		    };
	    }
	
	    return new SharedService();
	});








/*


function shareAjaxResponse( str ){
	str = strTrim( str );
	var arr = str.split( destMod_ModSeparator );
	for( var i = 0; i < arr.length; i++ ){
		var modAct_dataArr = arr[i].split( respModAct_DataSeparator );
		if( modAct_dataArr.length == 2 ){
			var data = modAct_dataArr[1];
			var mod_actArr = modAct_dataArr[0].split( destMod_ActSeparator );
			if( mod_actArr.length == 2 ){
				var mod = mod_actArr[0];
				var act = mod_actArr[1];
				
				var func = listenerMethodPrefix + mod + "_" + act;
				try{
					this[ func ]( data );
				}catch( ex ){
					alert(func + "  chka");
				}


			}
		}
	}
}

*/