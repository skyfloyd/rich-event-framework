var FrameModEditFormViewParent = function(){
	this.checkForm = function( dataStructure, messageFieldId ){
		var returnParams = {};
		
		var idPrefix = dataStructure.htmlIdPrefix;
		console.log( dataStructure.data );
		for( var fieldName in dataStructure.data ){
			
			if( typeof dataStructure.data[ fieldName ].check !== 'undefined' && dataStructure.data[ fieldName ].check.length > 0 ){
				for( var i = 0; i < dataStructure.data[ fieldName ].check.length; i++ ){
					if( dataStructure.data[ fieldName ].check[ i ] == "require" ){
						if( checkRequire( idPrefix, fieldName, dataStructure.data[ fieldName ].type ) ){
							returnParams = getElementValue( idPrefix, fieldName, dataStructure.data[ fieldName ].type, returnParams );
						}else{
							document.getElementById( messageFieldId ).style.color = "red";
							document.getElementById( messageFieldId ).innerHTML = "Please fill " + dataStructure.data[ fieldName ].name;
							return false;
						}
					}else{
						returnParams = getElementValue( idPrefix, fieldName, dataStructure.data[ fieldName ].type, returnParams );
					}
				}
			}else{
				returnParams = getElementValue( idPrefix, fieldName, dataStructure.data[ fieldName ].type, returnParams );
			}
		}
		
		return returnParams; // false;
	}
	
	function checkRequire( idPrefix, fieldName, type ){
		if( type == "text" || type == "textArea" ){
			if( trim( document.getElementById( (idPrefix + fieldName) ).value ) == "" ){
				return false;
			}
		}else
		if( type == "file" ){
			if( document.getElementById( (idPrefix + fieldName) ).files.length == 0 ){
				return false;
			}
		}
		
		return true;
	}
	
	function getElementValue( idPrefix, fieldName, type, returnParams ){
		if( type == "text" || type == "textArea" ){
			console.log( returnParams );
			if( typeof returnParams[ "textData" ] === 'undefined' ){
				returnParams[ "textData" ] = {};
			}
			returnParams[ "textData" ][ fieldName ] = trim( document.getElementById( (idPrefix + fieldName) ).value );
		}else
		if( type == "file" ){
			if( document.getElementById( (idPrefix + fieldName) ).files.length > 0 ){
				if( typeof returnParams[ "fileData" ] === 'undefined' ){
					returnParams[ "fileData" ] = {};
				}
				returnParams[ "fileData" ][ "file" ] = document.getElementById( (idPrefix + fieldName) );
			}
		}
		
		return returnParams;
	}
	
	function trim( str ){
		return str.replace(/^\s+|\s+$/gm,'');
	}
}