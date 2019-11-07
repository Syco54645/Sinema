Sinema.controller('EditTrailerController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

  $scope.model = {
    active: viewVars.trailer.active == "1",
    trailer: {
      id: viewVars.trailer.id,
      title: viewVars.trailer.title,
      summary: viewVars.trailer.summary,
      active: viewVars.trailer.active,
    }
  };

  $scope.changeActive = function () {
    if ($scope.model.active) {
      $scope.model.trailer.active = "1";
    } else {
      $scope.model.trailer.active = "0";
    }
  }

  $scope.saveTrailer = function (step) {

    var submitData = $scope.model.trailer;

    var promise = $http({
      url: '/trailer/ajaxSave',
      method: "POST",
      data: submitData
    });

    promise.then(function (response) {
      $window.scrollTo(0, 0);
    });

    promise.success(function(response) {
      console.log(response);
      if (response.status == "ok") {
        var message = 'Trailer Saved Successfully.';
        Flash.create('success', message, 0, {}, true);
      }
    });

    promise.error(function(response) {
      console.log(response);
    });
  }


}]);
