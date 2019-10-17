Sinema.controller('EditPrerollController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    active: viewVars.preroll.active == "1",
    preroll: {
      id: viewVars.preroll.id,
      title: viewVars.preroll.title,
      summary: viewVars.preroll.summary,
      active: viewVars.preroll.active,
      preroll_type_id: viewVars.preroll.preroll_type_id,
    }
  };

  $scope.changeActive = function () {
    if ($scope.model.active) {
      $scope.model.preroll.active = "1";
    } else {
      $scope.model.preroll.active = "0";
    }
  }

  $scope.savePreroll = function (step) {

    var submitData = $scope.model.preroll;

    var promise = $http({
      url: '/preroll/ajaxSave',
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
