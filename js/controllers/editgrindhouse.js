Sinema.controller('EditGrindhouseController', ['$scope', '$location', '$http', '$window', 'Flash', function($scope, $location, $http, $window, Flash) {

  $scope.dateFormats = {
    display: 'YYYY-MM-DD h:mm a',
    database: 'YYYY-MM-DD HH:mm:ss',
  }

  $scope.model = {
    grindhouse: {
      id: viewVars.grindhouse.id,
      title: viewVars.grindhouse.title,
      tagline: viewVars.grindhouse.tagline,
      showDate: viewVars.grindhouse.show_date ? moment(viewVars.grindhouse.show_date).format($scope.dateFormats.database): viewVars.grindhouse.show_date,
    },
    assembledFeature: viewVars.assembledFeature,
    creatingPlexPlaylist: false,
  };

  $scope.dateOptions = {
    dateDisabled: false,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };


 $scope.createPlexPlaylist = function () {

    var submitData = {
      id: viewVars.grindhouseId,
    };
    $scope.model.creatingPlexPlaylist = true;

    var promise = $http({
      url: '/grindhouse/ajaxCreatePlexPlaylist',
      method: "POST",
      data: submitData
    });

    promise.success(function(response) {
      if (response.status == "ok") {
        var message = 'Playlist Created Successfully.';
        Flash.create('success', message, 0, {}, true);
        $scope.model.creatingPlexPlaylist = false;
      }
    });

    promise.error(function(response) {
      console.log(response);
    });
  }

  $scope.saveGrindhouse = function (step) {

    var submitData = $scope.model.grindhouse;

    if (moment.isMoment($scope.model.grindhouse.showDate)) {
      submitData['showDate'] = $scope.model.grindhouse.showDate.format($scope.dateFormats.database);
    }

    var promise = $http({
      url: '/grindhouse/ajaxSave',
      method: "POST",
      data: submitData
    });

    promise.then(function (response) {
      $window.scrollTo(0, 0);
    });

    promise.success(function (response) {
      console.log(response);
      if (response.status == "ok") {
        var message = 'Grindhouse Saved Successfully.';
        Flash.create('success', message, 0, {}, true);
      }
    });

    promise.error(function (response) {
      console.log(response);
    });
  }

}]);
