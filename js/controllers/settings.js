Sinema.controller('SettingsController', ['$scope', '$location', '$http', 'Flash', function($scope, $location, $http, Flash) {

  $scope.model = {
    settings: {

    }
  };

  var initData = function () {
    for(var i = 0; i < viewVars.settings.length; i++) {
      var setting = viewVars.settings[i];
      $scope.model.settings[setting.setting_slug] = setting.setting_value
    }
    console.log($scope.model.settings)
  }

  $scope.save = function () {

    var submitData = {
      settings: $scope.model.settings,
    };

    var promise = $http({
      url: '/ajax/save-settings',
      method: "POST",
      data: submitData
    });

    promise.success(function(data) {
      console.log(data);
      if (data.status == "success") {
        var message = 'Settings Saved Successfully.';
        Flash.create('success', message, 0, {class: 'custom-class', id: 'custom-id'}, true);
      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }

  initData();
}]);
