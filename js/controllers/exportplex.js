Sinema.controller('ExportPlexController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    libraryType: '--------',
    libraryId: null,
    exporting: false,
    step: 1,
    csv: null,
    collectionName: 'sinema-trailers',
    exportType: 'file',
    identifierPrefix: 'sinema-trailer',
  };

  $scope.clickPlexLibrary = function (id, title) {
    $scope.model.libraryId = id;
  }

  $scope.export = function (step) {

    var submitData = {
      libraryType: $scope.model.libraryType,
      libraryId: $scope.model.libraryId,
      collectionName: $scope.model.collectionName,
      exportType: $scope.model.exportType,
    };
//    $scope.model.exporting = true;

    var promise = $http({
      url: '/ajax/export-plex',
      method: "POST",
      data: submitData
    });

   promise.success(function(response) {
      console.log(response);
      if (response.status == "ok") {
        $scope.model.exporting = false;
        $scope.model.csv = response.data.csv;
        console.log(response.data.csv)
      }
    });
    promise.error(function(response) {
      console.log(response);
      $scope.model.exporting = false;
    });
  }


}]);
