var FrameModSweepstakeList_ModuleObj = angular.module('FrameModSweepstakeList_Module', ['myFrameworkCoreModule']);

FrameModSweepstakeList_ModuleObj.controller('FrameModSweepstakeList_Controller', function($scope, myFrameworkCore) {
	var _ACTION__REFRESH_LIST = "refreshList";
	var _ACTION__GET_SWEEPSTAKE_ACTIVITY = "getSweepstakeActivity";
	var _ACTION__CREATE = "createSweepstake";
	var _ACTION__CLONE = "cloneSweepstake";
	var _ACTION__EDIT = "editSweepstake";
	var _ACTION__REMOVE = "removeSweepstake";
	
	var _SELF_ = this;
	var _ParentObject = null;
	$scope.$watch("_MODULE_ID_", function(){
		_ParentObject = new FrameModParent( $scope._MODULE_ID_ );
		
		//$http.post( FrameworkFunc.getUrl( _ACTION__REFRESH_LIST, _ParentObject.getModuleId() ), "" ).success( responseFromServer.bind( _SELF_ ) );
		myFrameworkCore.sendAjaxRequest( _ACTION__REFRESH_LIST, _ParentObject.getModuleId() );
		
	    $scope.$on((myFrameworkCore.listenerMethodPrefix + _ParentObject.getModuleId() + "_refershList"), function( event, data ) {
	    	$scope.listItems = data;
	    });
    });
	
	$scope.listItems = [];
	$scope.v = {};
	$scope.v.filterValue = 'all';
	
	$scope.eventFunctions = {};
	$scope.eventFunctions.clone = function( id ){
		if( confirm('Are you sure you want to clone this sweepstake?') ){
			var postParam = "sweepstakeId=" + id;
			//$http.post( FrameworkFunc.getUrl( _ACTION__CLONE, _ParentObject.getModuleId() ), postParam ).success( responseFromServer.bind( _SELF_ ) );
/*			$http({
			    method: 'POST',
			    url: FrameworkFunc.getUrl( _ACTION__CLONE, _ParentObject.getModuleId() ),
			    data: postParam,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).success(function(data, status, headers, config) {
				responseFromServer.bind( _SELF_ )( data );
			}); */
			myFrameworkCore.sendAjaxRequest( _ACTION__CLONE, _ParentObject.getModuleId(), null, null, postParam );
		}else{
			// Do nothing!
		}
	}
	$scope.eventFunctions.edit = function( id ){
		var postParam = "sweepstakeId=" + id;
		//$http.post( FrameworkFunc.getUrl( _ACTION__EDIT, _ParentObject.getModuleId() ), postParam ).success( responseFromServer.bind( _SELF_ ) );
/*		$http({
		    method: 'POST',
		    url: FrameworkFunc.getUrl( _ACTION__EDIT, _ParentObject.getModuleId() ),
		    data: postParam,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data, status, headers, config) {
			responseFromServer.bind( _SELF_ )( data );
		}); */
		myFrameworkCore.sendAjaxRequest( _ACTION__EDIT, _ParentObject.getModuleId(), null, null, postParam );
	}
	$scope.eventFunctions.remove = function( id ){
		var postParam = "sweepstakeId=" + id;
		//$http.post( FrameworkFunc.getUrl( _ACTION__REMOVE, _ParentObject.getModuleId() ), postParam ).success( responseFromServer.bind( _SELF_ ) );
/*		$http({
		    method: 'POST',
		    url: FrameworkFunc.getUrl( _ACTION__REMOVE, _ParentObject.getModuleId() ),
		    data: postParam,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data, status, headers, config) {
			responseFromServer.bind( _SELF_ )( data );
		}); */
		myFrameworkCore.sendAjaxRequest( _ACTION__REMOVE, _ParentObject.getModuleId(), null, null, postParam );
	}
	
	$scope.eventFunctions.create = function(){
		//$http.post( FrameworkFunc.getUrl( _ACTION__CREATE, _ParentObject.getModuleId() ), "" ).success( responseFromServer.bind( _SELF_ ) );
		myFrameworkCore.sendAjaxRequest( _ACTION__CREATE, _ParentObject.getModuleId() );
	}
	
	$scope.eventFunctions.filter = function(){
		var postParam = "filter=" + $scope.v.filterValue;
		//sendRequestViaAjax( FrameworkFunc.getUrl( _ACTION__REFRESH_LIST, _ParentObject.getModuleId() ), "POST", responseFromServer.bind( this ), postParam );
/*		$http({
		    method: 'POST',
		    url: FrameworkFunc.getUrl( _ACTION__REFRESH_LIST, _ParentObject.getModuleId() ),
		    data: postParam,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data, status, headers, config) {
			responseFromServer.bind( _SELF_ )( data );
		}); */
		myFrameworkCore.sendAjaxRequest( _ACTION__REFRESH_LIST, _ParentObject.getModuleId(), null, null, postParam );
	}
});
//FrameModSweepstakeList_Controller.$inject = ['$scope', 'myFrameworkCore'];