Sinema.controller('EditPrerollController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

  $scope.model = {
    active: viewVars.preroll.active == "1",
    preroll: {
      id: viewVars.preroll.id,
      title: viewVars.preroll.title,
      summary: viewVars.preroll.summary,
      active: viewVars.preroll.active,
      preroll_type_id: viewVars.preroll.preroll_type_id,
      preroll_series_id: viewVars.preroll.preroll_series_id,
      library_id: viewVars.preroll.library_id,
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

    if (submitData['id'] == undefined) { // this signifies that it is an insert rather than an update
      delete submitData['id'];
    }

    var promise = $http({
      url: '/preroll/ajaxSave',
      method: "POST",
      data: submitData
    });

    promise.then(function (response) {
      $window.scrollTo(0, 0);
    });

    promise.success(function(response) {
      console.log(response);
      if (response.status == "ok") {
        var message = 'Preroll Saved Successfully.';
        Flash.create('success', message, 0, {}, true);
        if (response.data.hasOwnProperty('preroll_id') && response.data.preroll_id) {
          window.location.href = '/admin/prerolls/edit/' + response.data.preroll_id
        }
      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }


}]);
