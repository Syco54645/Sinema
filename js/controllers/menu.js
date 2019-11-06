Sinema.controller('MenuController', ['$scope', '$location', '$http', '$window', 'Flash', function($scope, $location, $http, $window, Flash) {

  $scope.model = {
    nav: {},
  };

  sitemap = {
    grindhouse: [
      'grindhouse-create',
      'grindhouse-manage',
      'grindhouse-upcoming',
    ],
    library: [
      'film-manage',
      'preroll-manage',
      'trailer-manage',
    ],
    settings: [
      'admin-settings',
    ]
  }

  $scope.toggleDropdown = function (event) {
    var dataId = $(event.currentTarget).attr("data-id");
    if ($scope.model.nav[dataId] == 'open') {
      $scope.model.nav[dataId] = 'close';
    }
    else {
      $scope.model.nav[dataId] = 'open';
    }
    console.log($scope.model)
  }

  $scope.init = function () {
    $.each($('.sidebar .nav-item.sb-dropdown'), function (index, element) {
      var dataId = $(this).find('.nav-link').attr("data-id");
      $(this).find('nav-link').css( "background-color", "red !important" )
      $scope.model.nav[dataId] = $scope.shouldOpen(dataId)? 'open': 'closed';
    });
    console.log($scope.model)
  }

  $scope.isOpen = function (dataId) {
    //return true
    if ($scope.model.nav[dataId] == 'open') {
      return true;
    } else {
      return false;
    }
  }

  $scope.shouldOpen = function (dataId) {
    var shouldOpen = sitemap[dataId].includes(viewVars.currentRoute);
    console.log("menuItem is active:",  shouldOpen)
    return shouldOpen;
  }

  $scope.isActive = function (routeName) {
    return viewVars.currentRoute == routeName;
  }

  $scope.init();
}]);
