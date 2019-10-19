var ngModules = ['angular.filter', 'ngFlash'];
var Sinema = angular.module('Sinema', ngModules);

Sinema.controller('RootController', ['$rootScope', '$http', '$timeout', '$scope', function($rootScope, $http, $timeout, $scope) {
  $rootScope.viewVars = viewVars;

}]);
Sinema.config(function($locationProvider) {
  if(viewVars.currentRouteName == 'live-auction' || viewVars.currentRouteName == 'live-auction-page'){
    $locationProvider.html5Mode({
      enabled: true,
      requireBase: false
    }).hashPrefix('!');
  }
});

Sinema.config(function($httpProvider){
  // Use x-www-form-urlencoded Content-Type
  //$httpProvider.defaults.headers.post['Content-Type'] = 'Content-Type: application/json';
});