Sinema.controller('EditFilmController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

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

    promise.then(function (response) {
      $window.scrollTo(0, 0);
    });

    promise.success(function(response) {
      console.log(response);
      if (response.status == "ok") {
        var message = 'Film Saved Successfully.';
        Flash.create('success', message, 0, {}, true);
      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }


}]);
