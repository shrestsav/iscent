/**
 * Created by Interactive Media on 8/30/14.
 */
/*
* use
* obj=angular.module("angular", []); create obj of module use -> <html ng-app="angular">
* obj.controller("controllername",function ($scope){   // use html ng-app="angular" ng-controller="controllername">
*   $scope.varibalename = "valueInit";
*   //call variable name -> <b> {{varibalename}} </b>
*   or // <b ng-bind="varibalename"></b> it replace inner value to angular,
*
*   $scope.fun = function(){
*       $scope.varibalename = "valueChange";
*   }
*
*  <i ng-init="fun('value')" ></i>
*
* })
*
* */



// Code goes here
app=angular.module("angular", []);

app.controller("angularController", function checkboxController($scope) {
   $scope.MyCntrl=function($scope) {
        $scope.submitted= 'not submitted'
        $scope.mySubmit = function(){
            $scope.submitted = 'submitted';
        }
    };

});

app2=angular.module("inventory0", []);
app2.controller("inventoryControll", function ($scope) {
    $scope.inventoryProducts="";
    $scope.invenFun=function(value){
        $scope.inventoryProducts=value;
    }
});





