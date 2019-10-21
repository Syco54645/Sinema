Sinema.controller('SettingsController', ['$scope', '$location', '$http', 'Flash', function($scope, $location, $http, Flash) {

  $scope.model = {
    settings: {},
  };

  $scope.selectizeConfigs = {
    'kept-subgenres': {
      plugins: ['remove_button'],
      create: true,
      delimiter: ';',
      placeholder: 'Type something to add subgenres to keep',
      options: viewVars.keptSubgenresArray.map(function(x) { return { item: x }; }),
      valueField: 'item',
      labelField: 'item',
      persist: false,
    },
  };

  $scope.selectizeModels = {
    'kept-subgenres': viewVars.keptSubgenresArray,
  };

  var initData = function () {
    for(var i = 0; i < viewVars.settings.length; i++) {
      var setting = viewVars.settings[i];
      $scope.model.settings[setting.setting_slug] = setting.setting_value;
    }
  }

  $scope.save = function () {

    // convert kept subgenres to a string separated by ;
    $scope.model.settings['kept-subgenres'] = $scope.selectizeModels['kept-subgenres'].join(';');
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
        viewVars.sinemaSettings = data.sinemaSettings;
      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }

  initData();
}]);
