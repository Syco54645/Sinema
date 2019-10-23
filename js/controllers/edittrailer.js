Sinema.controller('EditTrailerController', ['$scope', '$location', '$http', function($scope, $location, $http) {

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

    promise.success(function(data) {
      console.log(data);
      if (data.status == "success") {
        location.reload();
      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }


}]);
