var FrameModSweepstakeOverview_manager = function(){
	var _ParentObject = null;
	
	var _ACTION__ACTIVE = "activateSweepstake";
	var _ACTION__PASSIVE = "passivateSweepstake";
	
	var statisticsData = null;
	var sweepstakeId = null;
	
	var isActive = null;
	var activateButtonId = null;
	var containerId = null;
	var sumContainerId = null;
	
	this.startWork = function( modId, sId, sd, ia, aId, cId, scId ){
		_ParentObject = new FrameModParent( modId );
		sweepstakeId = sId;
		statisticsData = sd;
		isActive = ia;
		activateButtonId = aId;
		containerId = cId;
		sumContainerId = scId;
		
		document.getElementById( activateButtonId ).onclick = changeActivity.bind( this );
		drawStatistics();
		drawActivityButton();
	}
	
	
	function drawStatistics(){
		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
		var categories = [ {"name": "view", "caption": "View", "value": 0, "color": "red"}, {"name": "enter", "caption": "Enter", "value": 0, "color": "green"}, {"name": "invite", "caption": "Invite", "value": 0, "color": "blue"}, {"name": "publish", "caption": "Publish", "value": 0, "color": "orange"} ];		
		var complateData = {};
		var lastDate = null;
		var dateList = [];
		
		var data = statisticsData;
				
		for( var i = 0; i < data.length; i++ ){
			if( lastDate != null ){
				var emptyDate = ((lastDate / 1) + (60 * 60 * 24));
				var edStr = new Date( emptyDate * 1000 ).getDate() + " " + months[ (new Date( emptyDate * 1000 ).getMonth() - 1) ];
				var sStr = new Date( data[ i ][ "date" ] * 1000 ).getDate() + " " + months[ (new Date( data[ i ][ "date" ] * 1000 ).getMonth() - 1) ];
				while( emptyDate < (data[ i ][ "date" ] / 1) && edStr !== sStr ){
					dateObj = new Date( emptyDate * 1000 );
					//dateList[ dateList.length ] = dateObj.getDate() + " " + months[dateObj.getMonth() - 1];
					dateList[ dateList.length ] = dateObj.getDate() + " " + months[dateObj.getMonth() - 1];

					
					complateData[ emptyDate ] = {};
					for( var k = 0; k < categories.length; k++ ){
						complateData[ emptyDate ][ categories[ k ][ "name" ] ] = 0;
					}
					
					emptyDate += (60 * 60 * 24);
					edStr = new Date( emptyDate * 1000 ).getDate() + " " + months[ (new Date( emptyDate * 1000 ).getMonth() - 1) ];
				}
			}

			var dateObj = new Date( data[ i ][ "date" ] * 1000 );
			//dateList[ dateList.length ] = dateObj.getDate() + " " + months[dateObj.getMonth() - 1];
			dateList[ dateList.length ] = dateObj.getDate() + " " + months[dateObj.getMonth() - 1];


			complateData[ data[ i ][ "date" ] ] = {};
			for( var k = 0; k < categories.length; k++ ){
				complateData[ data[ i ][ "date" ] ][ categories[ k ][ "name" ] ] = data[ i ][ categories[ k ][ "name" ] ];
			}
			
			lastDate = data[ i ][ "date" ] / 1;
		}
		
	
		var grafFromatedData = [];
		for( var i = 0; i < categories.length; i++ ){
			grafFromatedData[ i ] = {};
			grafFromatedData[ i ][ 'name' ] = categories[ i ][ "caption" ];
			grafFromatedData[ i ][ 'data' ] = [];
			grafFromatedData[ i ][ 'color' ] = categories[ i ][ "color" ];
			
			for( date in complateData ){
				grafFromatedData[ i ][ 'data' ][ grafFromatedData[ i ][ 'data' ].length ] = (complateData[ date ][ categories[ i ][ "name" ] ] / 1);
				categories[ i ][ "value" ] += (complateData[ date ][ categories[ i ][ "name" ] ] / 1);
			}
		}
		
		console.log( grafFromatedData );
		console.log( dateList );
		
		document.getElementById( containerId ).style.width = 1000;
		document.getElementById( containerId ).style.height = 400;
		
		
		$( document.getElementById( containerId ) ).highcharts({
			title: {
				text: 'Overview',
				x: -20 //center
			},
			xAxis: {
				categories: dateList
			},
			yAxis: {
				title: {
					text: 'Users Count'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				valueSuffix: ' user'
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: grafFromatedData
		});	
		
		drawStatisticsSumView.bind( this )( categories );
	}
	
	function drawActivityButton(){
		if( isActive ){
			document.getElementById( activateButtonId ).value = "Passivate Sweepstake";
		}else{
			document.getElementById( activateButtonId ).value = "Activate Sweepstake";
		}
	}
	
	function drawStatisticsSumView( categories ){
		var view = "<table align='center'><tr>";
		for( var i = 0; i < categories.length; i++ ){
			view += "<td><div style='background-color: " + categories[ i ][ "color" ] + "; font-weight: bold; font-size: 15px; color: white; text-align: center; height: 50px; width: 100px; font-family: arial; padding-top: 10px;'><div>" + categories[ i ][ "caption" ] + "</div><div>" + categories[ i ][ "value" ] + "</div></div></td>";
		}
		view += "</tr></table>";
		
		document.getElementById( sumContainerId ).innerHTML = view;
	}
	
	function changeActivity( e ){
		document.getElementById( activateButtonId ).value = "Loading...";
		var paramsArray = {};
		paramsArray[ "sweepstakeId" ] = sweepstakeId;
		if( isActive ){
			submitFormViaAjax( FrameworkFunc.getUrl( _ACTION__PASSIVE, _ParentObject.getModuleId() ), responseFromServer.bind( this ), paramsArray, null );
		}else{
			submitFormViaAjax( FrameworkFunc.getUrl( _ACTION__ACTIVE, _ParentObject.getModuleId() ), responseFromServer.bind( this ), paramsArray, null );
		}
		
		isActive = !isActive;
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
		if( !_ParentObject.checkOneResponse( resObj ) ){ // NO ERROR, NO PERRMISSION PROBLEM ... normal work
			if( typeof resObj[ "moduleAction" ] !== 'undefined' ){ //   "defaultView" ){
				if( resObj[ "moduleAction" ] == "activeSweepstake" ){
					drawActivityButton.bind( this )();
				}else
				if( resObj[ "moduleAction" ] == "passiveSweepstake" ){
					drawActivityButton.bind( this )();
				}
			}
		}
	}
}