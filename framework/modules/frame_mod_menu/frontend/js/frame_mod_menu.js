var FrameModMenu_manager = function(){
	var _MODULE_ID = "Menu";
	
	var parentElement = null;
	var itemIdSeparater = null;
	
	this.startWork = function( parentEl, itemSelectedClass, itemClass, itemName, idSep ){
		parentElement = parentEl;
		itemIdSeparater = idSep;
		
		var itemsList = document.getElementsByName( itemName );
		for( var i = 0; i < itemsList.length; i++ ){
			if( itemsList[ i ].className == itemClass ){
				itemsList[ i ].onclick = changeItem.bind( this );
			}
		}
	}
	
	function changeItem( e ){
		var target = e.target || e.srcElement;
		
		var parts = target.id.split( itemIdSeparater );
		document.location = FrameworkFunc.getUrl( parts[ 0 ], _MODULE_ID, null, null, false ) + (parts[ 1 ] != "" ? ("&" + parts[ 1 ]) : "");
	}
}