Sinema.controller('ExportPlexController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    importType: '--------',
    libraryId: null,
    importing: false,
    step: 1,
    csv: null,
  };

  $scope.clickPlexLibrary = function (id, title) {
    $scope.model.libraryId = id;
  }

  $scope.export = function (step) {

    var submitData = {
      type: $scope.model.importType,
    };
    submitData.libraryId = $scope.model.libraryId;
//    $scope.model.importing = true;

    var promise = $http({
      url: '/ajax/export-plex',
      method: "POST",
      data: submitData
    });

   promise.success(function(response) {
      console.log(response);
      if (response.status == "ok") {
        $scope.model.importing = false;
        $scope.model.csv = response.data.csv;
        console.log(response.data.csv)
      }
    });
    promise.error(function(response) {
      console.log(response);
      $scope.model.importing = false;
    });
  }


}]);
