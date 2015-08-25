////////////////////////////////////////////////////////////////////////////////
//
/////////////////////////////////////////////////////////////////////////////////

function getNumberWithoutPX( number ){
	if( number.indexOf("px") != -1 || number.indexOf("PX") != -1 )
		number = number.substr( 0, (number.length - 2) );
		
	return (number / 1);
}

function getRightEvent( e ){
	if( !e ) var e = window.event;
	
	return e;
}


function strTrim( str ){
	var i = str.indexOf( " " );
	while( i == 0 ){
		str = str.substr(1, (str.length - 1));
		i = str.indexOf( " " );
	}
	
	i = str.lastIndexOf( " " );
	while( i == 0 ){
		str = str.substr(0, (str.length - 1));
		i = str.lastIndexOf( " " );
	}
	
	return str;
}

function GlobalLib(){
}

GlobalLib.validateEmail = function( email ){ 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

GlobalLib.randomString = function( length ){
	if( typeof length == "undefined" ){
		length = 10;
	}
	
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i = 0; i < length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

GlobalLib.redirectByPost = function( url, params ){
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);

	if( typeof params === "string" ){
		params = GlobalLib.getParamsFromUriString( params );
	}

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

GlobalLib.getParamsFromUriString = function( uriString ){
    var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        //decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
		decode = function (s) { return s.replace(pl, " "); },
		query  = window.location.search.substring(1);

    urlParams = {};
	//while (match = search.exec( query ))
    while (match = search.exec( uriString )){
       urlParams[decode(match[1])] = decode(match[2]);
	}
	
	return urlParams;
}

GlobalLib.getCorrectProtocolUrl = function( url ){
	var parts = url.split( '//' );
	if( parts.length < 2 ){
		return url;
	}
	
	parts[ 0 ] = location.protocol;
	return parts.join( '//' );
}