Sinema.controller('EditTrailerController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

  $scope.model = {
    active: viewVars.trailer.active == "1",
    trailer: {
      id: viewVars.trailer.id,
      title: viewVars.trailer.title,
      summary: viewVars.trailer.summary,
      active: viewVars.trailer.active,
      library_id: viewVars.trailer.library_id,
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

    if (submitData['id'] == undefined) { // this signifies that it is an insert rather than an update
      delete submitData['id'];
    }

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
        if (response.data.hasOwnProperty('trailer_id') && response.data.trailer_id) {
          window.location.href = '/admin/trailers/edit/' + response.data.trailer_id
        }
      }
    });

    promise.error(function(response) {
      console.log(response);
    });
  }


}]);
