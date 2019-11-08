Sinema.controller('ImportPlexController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

  $scope.model = {
    processingMode: 'update',
    importType: '',
    libraryAlias: null,
    libraryId: null,
    importing: false,
    step: 1,
    numFilmsToBulkProcess: 30,
    filmBulkOffset: 0,
  };

  //$scope.model = {"importType":"movie","libraryId":5,"importing":false,"step":3,"numFilmsToBulkProcess":30,"filmBulkOffset":150}

  $scope.selectLibrary = function (libraryType) {

    for (var i = 0; i < viewVars.libraries.length; i++) {
        if ($scope.model.libraryId == viewVars.libraries[i].id) {
            $scope.model.importType = viewVars.libraries[i].library_type;
        }
    }

  }

  $scope.clickPlexLibrary = function (id, title) {

    $scope.model.libraryId = id;
    $scope.model.libraryAlias = title;
  }

  $scope.importMovie = function (step) {

    var submitData = {
      type: $scope.model.importType,
    };
    if ($scope.model.step == 1) {
      submitData.libraryId = $scope.model.libraryId;
      if ($scope.model.processingMode == 'import') {
        submitData.libraryAlias = $scope.model.libraryAlias;
      }
    } else if ($scope.model.step == 2) {
      submitData.numFilms = $scope.model.numFilmsToBulkProcess;
      submitData.offset = $scope.model.filmBulkOffset;
    }
    $scope.model.importing = true;

    var promise = $http({
      url: '/ajax/import-plex/' + $scope.model.step +'/',
      method: "POST",
      data: submitData
    });

    promise.success(function(response) {
      console.log(response);
      $scope.model.importing = false;

      if ($scope.model.importType == 'movie') {
        if (step > 1) {
          $scope.model.filmBulkOffset += $scope.model.numFilmsToBulkProcess;
        }

        if (response.status == "ok") {
          var incrementStep = true;
          if ($scope.model.step == 2) {
            if (response.more == true) {
              // there is more data so do not increment the step
              // increase the offset
              // go back and import more
              incrementStep = false;
              $scope.model.filmBulkOffset += $scope.model.numFilmsToBulkProcess;
              $scope.importMovie();
            }
          }
          if (incrementStep) {
            $scope.model.step++;
          }
        }
      } else if ($scope.model.importType == 'preroll' || $scope.model.importType == 'trailer') {
        $window.scrollTo(0, 0);
        var type = $scope.model.importType
        var message = type.charAt(0).toUpperCase() + type.slice(1) + 's ' + prettyProcessingMode() + ' Successfully.';
        Flash.create('success', message, 0, {}, true);
      }
    });

    promise.error(function(response) {
      console.log(response);
      $scope.model.importing = false;
    });
  } // end movie promise handler

  var prettyProcessingMode = function () {
    if ($scope.model.processingMode == 'update') {
      return 'Updated';
    } else {
      return 'Imported';
    }
  }


}]);
