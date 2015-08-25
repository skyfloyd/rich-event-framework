if( typeof XMLHttpRequest == "undefined" ){
	
	XMLHttpRequest = function(){
	  try{ return new ActiveXObject("Msxml2.XMLHTTP.6.0") }catch(e){}
	  try{ return new ActiveXObject("Msxml2.XMLHTTP.3.0") }catch(e){}
	  try{ return new ActiveXObject("Msxml2.XMLHTTP") }catch(e){}
	  try{ return new ActiveXObject("Microsoft.XMLHTTP") }catch(e){}
	  throw new Error("This browser does not support XMLHttpRequest or XMLHTTP.")
	};
}

function sendRequestViaAjax( url, requestType, responseFunction, postParameters ){
	var xmlhttp = new XMLHttpRequest();
//	alert("Hello world");
	var ajaxType = true;
	
	if( ajaxType ){
		xmlhttp.onreadystatechange = 
			function () {
				if( xmlhttp.readyState == 4 ){
					var xmlResponse = xmlhttp.responseText;	
					myFunction = (responseFunction)?responseFunction:parseResponse;
					myFunction( xmlResponse );
				}
			};
	}
	
	var postInfo = "";
	if( requestType == "POST" ){
		xmlhttp.open('POST', url, ajaxType);
		postInfo = postParameters;
	}else{
		xmlhttp.open('GET', url, ajaxType);
	}
		
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(postInfo);
	
	if( !ajaxType ){
		if ( xmlhttp.readyState == 4 ){
			var xmlResponse = xmlhttp.responseText;	
			myFunction = (responseFunction)?responseFunction:parseResponse;
			myFunction( xmlResponse );
		}
	}
}


function submitFormViaAjax( url, responseFunction, paramsArray, fileObj ){
//	var client = new XMLHttpRequest();
	
	var formData = new FormData();
	if( typeof fileObj !== 'undefined' && fileObj !== null ){
		formData.append(fileObj.name, fileObj.files[0]);
	}
	if( typeof paramsArray !== 'undefined' && paramsArray !== null ){
		for( key in paramsArray ){
			formData.append(key, paramsArray[ key ]);
		}
	}
	
 $.ajax({
     url: url,
     type: 'POST',
     cache: false,
     data: formData,
     processData: false,
     contentType: false,
     success: function (response) {
     console.log(response);
	 responseFunction( response );
  }
  });
/*	
	client.open( "POST", url, true );
	//client.setRequestHeader("Content-Type", "multipart/form-data");
	//client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	client.send( formData ); 
	
	client.onreadystatechange = function(){
		if( client.readyState == 4 && client.status == 200 ){
			console.log( client.statusText );
			console.log( client.responseText );
			responseFunction( client.responseText );
		}
	}
	*/
}