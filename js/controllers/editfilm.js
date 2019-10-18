Sinema.controller('EditFilmController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    active: viewVars.film.active == "1",
    film: {
      id: viewVars.film.id,
      title: viewVars.film.title,
      summary: viewVars.film.summary,
      active: viewVars.film.active,
    }
  };

  $scope.changeActive = function () {
    if ($scope.model.active) {
      $scope.model.film.active = "1";
    } else {
      $scope.model.film.active = "0";
    }
  }

  $scope.saveFilm = function (step) {

    var submitData = $scope.model.film;

    var promise = $http({
      url: '/film/ajaxSave',
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
