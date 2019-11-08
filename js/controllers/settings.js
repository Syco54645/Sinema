Sinema.controller('SettingsController', ['$scope', '$location', '$http', 'Flash', '$window', function($scope, $location, $http, Flash, $window) {

  $scope.model = {
    settings: {},
  };

  $scope.selectizeConfigs = {
    'kept-tags': {
      plugins: ['remove_button'],
      create: true,
      delimiter: ';',
      placeholder: 'Type something to add tags to keep',
      options: viewVars.keptTagsArray.map(function(x) { return { item: x }; }),
      valueField: 'item',
      labelField: 'item',
      persist: false,
    },
  };

  $scope.selectizeModels = {
    'kept-tags': viewVars.keptTagsArray,
  };

  $scope.isFieldDisabled = function (setting) {
    if (setting.conditional) {
      // this is shitty but works for what i need right now. will need expanded. FIXME
      if ($scope.model.settings[setting.conditional] == '0') {
        return true;
      }
    }
  }

  var initData = function () {
    for(var i = 0; i < viewVars.settings.length; i++) {
      var setting = viewVars.settings[i];
      $scope.model.settings[setting.setting_slug] = setting.setting_value;
    }
  }

  $scope.save = function () {

    // convert kept tags to a string separated by ;
    Flash.clear();
    $scope.model.settings['kept-tags'] = $scope.selectizeModels['kept-tags'].join(';');
    var submitData = {
      settings: $scope.model.settings,
    };

    var promise = $http({
      url: '/ajax/save-settings',
      method: "POST",
      data: submitData
    });

    promise.then(function (response) {
      $window.scrollTo(0, 0);
    });

    promise.success(function (response) {
      console.log(response);
      if (response.status == "ok") {
        var message = 'Settings Saved Successfully.';
        Flash.create('success', message, 0, {}, true);
        viewVars.sinemaSettings = response.sinemaSettings;
      }
    });

    promise.error(function (response) {
      console.log(response);
    });
  }

  initData();
}]);
