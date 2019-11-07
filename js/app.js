var ngModules = ['angular.filter', 'ngFlash', 'selectize'];

if (viewVars.currentRoute == 'grindhouse-edit') {
  ngModules = ngModules.concat(['ui.bootstrap.datetimepicker', 'ui.dateTimeInput']);
}

var Sinema = angular.module('Sinema', ngModules);

Sinema.controller('RootController', ['$rootScope', '$http', '$timeout', '$scope', function($rootScope, $http, $timeout, $scope) {
  $rootScope.viewVars = viewVars;

  $rootScope.pageTitle = function () {
    return viewVars.title ? viewVars.title: 'GRINDHAUS!!!!';
  }

  $rootScope.pageSubtitle = function () {
    return viewVars.subtitle ? viewVars.subtitle: null;
  }
}]);

Sinema.config(function($locationProvider) {
  /*if(viewVars.currentRouteName == 'asddsa'){
    $locationProvider.html5Mode({
      enabled: true,
      requireBase: false
    }).hashPrefix('!');
  }*/
});

Sinema.config(function($httpProvider){
  // Use x-www-form-urlencoded Content-Type
  //$httpProvider.defaults.headers.post['Content-Type'] = 'Content-Type: application/json';
});


Sinema.filter('cut', function () {
  // taken from https://stackoverflow.com/questions/18095727/limit-the-length-of-a-string-with-angularjs
  return function (value, wordwise, max, tail) {
    if (!value) return '';
    if (!max) max = 200;

    max = parseInt(max, 10);
    if (!max) return value;
    if (value.length <= max) return value;

    value = value.substr(0, max);
    if (wordwise) {
      var lastspace = value.lastIndexOf(' ');
      if (lastspace !== -1) {
        //Also remove . and , so its gives a cleaner result.
        if (value.charAt(lastspace-1) === '.' || value.charAt(lastspace-1) === ',') {
          lastspace = lastspace - 1;
        }
        value = value.substr(0, lastspace);
      }
    }
    return value + (tail || ' …');
  };
});
