Sinema.controller('CreateGrindhouseController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    step: 1,
    search: {
        selected: {
            genre: true,
            subgenre: true,
            prerolls: true,
            trailers: true,
        },
        options: {
          genreSubgenreIntersect: false,
          genreMode: 'matchAny',
          subgenreMode: 'matchAny',
        },
        criteria: {
            //genre: [],
            genreId: [],
            //subgenre: [],
            subgenreId: [],
            prerolls: {
                stayInSeries: true,
                selectedSeries: 'color-swirl',
            },
            trailers: {
                adult: true,
                number: 8,
            }
        },
    },
  };

  $scope.selectizeConfigs = {
    genre: {
      plugins: ['remove_button'],
      create: false,
      placeholder: 'Genres',
      options: viewVars.genres,
      valueField: 'id',
      labelField: 'genre',
      persist: false,
    },
    subgenre: {
      plugins: ['remove_button'],
      create: false,
      placeholder: 'Tags',
      options: viewVars.subgenres,
      valueField: 'id',
      labelField: 'subgenre',
      persist: false,
    },
  };

/*  $scope.selectGenre = function (genreSlug, genreId) {

    if ($scope.model.search.criteria.genre.indexOf(genreSlug) != -1) {
        var index = $scope.model.search.criteria.genre.indexOf(genreSlug);
        if (index !== -1) {
            $scope.model.search.criteria.genre.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.genre.push(genreSlug);
    }

    if ($scope.model.search.criteria.genreId.indexOf(genreId) != -1) {
        var index = $scope.model.search.criteria.genreId.indexOf(genreId);
        if (index !== -1) {
            $scope.model.search.criteria.genreId.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.genreId.push(genreId);
    }
  }

  $scope.selectSubgenre = function (subgenreSlug, subgenreId) {

    if ($scope.model.search.criteria.subgenre.indexOf(subgenreSlug) != -1) {
        var index = $scope.model.search.criteria.subgenre.indexOf(subgenreSlug);
        if (index !== -1) {
            $scope.model.search.criteria.subgenre.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.subgenre.push(subgenreSlug);
    }

    if ($scope.model.search.criteria.subgenreId.indexOf(subgenreId) != -1) {
        var index = $scope.model.search.criteria.subgenreId.indexOf(subgenreId);
        if (index !== -1) {
            $scope.model.search.criteria.subgenreId.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.subgenreId.push(subgenreId);
    }

  }*/


  $scope.createGrind = function (step) {

    var submitData = {
      search: $scope.model.search,
    };

    var promise = $http({
      url: '/grindhouse/ajaxCreate',
      method: "POST",
      data: submitData
    });

    promise.success(function(data) {
      console.log(data);
      if (data.status == "success") {

      }
    });

    promise.error(function(data) {
      console.log(data);
    });
  }


}]);
