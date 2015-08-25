var destMod_ModSeparator = "~"; 
var destMod_ActSeparator = ">"; 
var respModAct_DataSeparator = "^";
var listenerMethodPrefix = "modListener_";
var effectExRootDir = location.href + "/";
//var effectExRootDir = "";

var FrameworkFunc = function(){};

FrameworkFunc.checkResponse = function( res ){
	if( res[ 0 ] != undefined ){
		for( var i = 0; i < res.length; i++ ){
			var result = FrameworkFunc.checkOneResponse( res[ i ] );
			if( !result ){
				return result;
			}
		}
	}else{
		return FrameworkFunc.checkOneResponse( res );
	}
	
	return true;
}

FrameworkFunc.checkOneResponse = function( res ){
	if( res[ "frameworkAction" ] != undefined ){
		if( res[ "frameworkAction" ] == "changeTemplate" ){
			GlobalLib.redirectByPost( effectExRootDir, ("templateId=" + res[ "frameworkActionParam" ]) );
			//document.location = effectExRootDir + "?templateId=" + res[ "frameworkActionParam" ];
			return false;
		}
	}
	
	return true;
}


FrameworkFunc.getUrl = function( actionId, moduleId, templateId, getParams, ajaxCall ){
	templateId = ((typeof templateId !== 'undefined' && templateId !== null) ? templateId : _CURRENT_TEMPLATE_ID);
	getParams = ((typeof getParams !== 'undefined' && getParams !== null) ? ("&" + getParams) : "");
	ajaxCall = ((typeof ajaxCall !== 'undefined' && ajaxCall !== null) ? ajaxCall : true);
	
	return effectExRootDir + "?actionId=" + actionId + "&moduleId=" + moduleId + "&templateId=" + templateId + (ajaxCall ? "&ajaxCall=1" : "") + getParams;
}

/*
function getAjaxRequest( reqMethod, getParams, postParams ){
	var url = effectExRootDir + "index.php";
	var postP = "";
	var method = "GET";
	
	if( reqMethod == "GET" ){
		method = "GET";
		url += "?" + getParams;
	}else{
		method = "POST";
		postP = postParams;
	}
	
	sendRequestViaAjax( url, method, shareAjaxResponse, postP );
}



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