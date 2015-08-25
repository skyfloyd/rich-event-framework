var appType_FaceBook	= "FaceBook";
var appType_Simple		= "Simple";
var currentApplication 	= "Simple";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_setObjectStyle
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_setObjectStyle( obj, styleName, styleValue ){
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		 obj.setStyle( styleName, styleValue );
	}else{
/////////////////////////////////////////// Simple JavaScript
		styleName = styleName.toLowerCase();
		switch( styleName ){
			case "left"				:	obj.style.left = styleValue;			break;
			case "top" 				:  	obj.style.top = styleValue;				break;
			case "display" 			:   obj.style.display = styleValue;			break;
			case "backgroundcolor" 	:   obj.style.backgroundColor = styleValue;	break;
			default :
			   alert("WRONG VALUES - '" + styleName + "' -> '" + styleValue + "'");
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_getObjectStyle
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_getObjectStyle( obj, styleName ){
	var value = null;
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		 value = obj.getStyle( styleName );
	}else{
/////////////////////////////////////////// Simple JavaScript
		styleName = styleName.toLowerCase();
		switch( styleName ){
			case "left"		:	value = obj.style.left;		break;
			case "top" 		:  	value = obj.style.top;		break;
			case "display" 	:   value = obj.style.display;	break;
			default :
			   alert("WRONG VALUES - '" + styleName + "' -> '" + styleValue + "'");
		}
	}
	
	return value;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_getEventAttribute
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_getEventAttribute( e, attributeName ){
	var value = null;
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		switch( attributeName ){
			case "pageX"	:	value = e.pageX;		break;
			case "pageY"	:  	value = e.pageY;		break;
			default :
			   alert("WRONG EVENT VALUE - '" + attributeName + "'");
		}
	}else{
/////////////////////////////////////////// Simple JavaScript
		switch( attributeName ){
			case "pageX"	:
				if (e.pageX){
					value = e.pageX;
				}else 
				if (e.clientX) 	{
					value = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
				}else
					alert("unknow Browser type");
			break;
			case "pageY"	:
				if (e.pageY){
					value = e.pageY;
				}else 
				if (e.clientY) 	{
					value = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
				}else
					alert("unknow Browser type");
			break;
			default :
			   alert("WRONG EVENT VALUE - '" + attributeName + "'");
		}
	}
	
	return value;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_addListenerToObject
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_addListenerToObject( obj, listenerType, callMethod ){
	listenerType = listenerType.toLowerCase();
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		obj.addEventListener( listenerType, callMethod );
	}else{
/////////////////////////////////////////// Simple JavaScript
		switch( listenerType ){
			case "onmousedown"	:	obj.onmousedown = callMethod;	break;
			case "onmouseup"	:	obj.onmouseup = callMethod;		break;
			case "onmouseover"	:	obj.onmouseover = callMethod;	break;
			case "onmouseout"	:	obj.onmouseout = callMethod;	break;
			case "onmousemove"	:	obj.onmousemove = callMethod;	break;
			default :
			   alert("WRONG Listener Type - '" + listenerType + "'");
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_getObjectAttribute
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_getObjectAttribute( obj, attributeName ){
	var value = null;
	attributeName = attributeName.toLowerCase();
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		switch( attributeName ){ 
			case "value"		:	value = obj.getValue();			break;
			case "href"			:	value = obj.getHref();			break;
			case "id"			:	value = obj.getId();			break;
			case "name"			:	value = obj.getName();			break;
			case "childnodes"	:	value = obj.getChildNodes();	break;
			case "firstchild"	:	value = obj.getFirstChild();	break;
			default :
			   alert("WRONG Attribute Name - '" + attributeName + "'");
		}
	}else{
/////////////////////////////////////////// Simple JavaScript
		switch( attributeName ){
			case "value"		:	value = obj.value;		break;
			case "href"			:	value = obj.href;		break;
			case "id"			:	value = obj.id;			break;
			case "name"			:	value = obj.name;		break;
			case "childnodes"	:	value = obj.childNodes;	break; 
			case "firstchild"	:	value = obj.firstChild;	break;
			default :
			   alert("WRONG Attribute Name - '" + attributeName + "'");
		}
	}
	
	return value;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_setObjectAttribute
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_setObjectAttribute( obj, attributeName, attributeValue ){
	attributeName = attributeName.toLowerCase();
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		switch( attributeName ){ 
			case "value"	:	obj.setValue( attributeValue );	break;
			case "href"		:	obj.setHref( attributeValue );	break;
			case "id"		:	obj.setId( attributeValue );	break;
			case "name"		:	obj.setName( attributeValue );	break;
			default :
			   alert("WRONG Attribute Name - '" + attributeName + "'");
		}
	}else{
/////////////////////////////////////////// Simple JavaScript
		switch( attributeName ){
			case "value"	:	obj.value = attributeValue;		break;
			case "href"		:	obj.href = attributeValue;		break;
			case "id"		:	obj.id = attributeValue;		break;
			case "name"		:	obj.name = attributeValue;		break;
			default :
			   alert("WRONG Attribute Name - '" + attributeName + "'");
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_getObjectInnerText
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_getObjectInnerText( obj ){
	var value = -1;
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		value = multiApp_getObjectAttribute( obj, "firstChild" ).nodeValue; // getNodeValue(); - maybe
	}else{
/////////////////////////////////////////// Simple JavaScript
		//value = obj.innerHTML;
		value = multiApp_getObjectAttribute( obj, "firstChild" ).nodeValue;
	}
	
	return value;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// multiApp_setObjectContent
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function multiApp_setObjectContent( obj, content, contentType ){
	contentType = contentType.toLowerCase();
/////////////////////////////////////////// FaceBook
	if( currentApplication == appType_FaceBook){
		//////////////////////// remove all previous content
		var childCount = obj.getChildNodes().length;
      	while( childCount-- > 0 ){
			root.removeChild( root.getLastChild() );
		}

		//////////////////////// sets new content
		if( contentType == "xhtml" )
			root.setInnerXHTML( content );
		else if( contentType == "fbml" )
			root.setInnerFBML( content ); // content is Fb:js-string Var (name)
		else
			root.setTextValue( content );
	}else{
/////////////////////////////////////////// Simple JavaScript
		obj.innerHTML = content;
	}
}