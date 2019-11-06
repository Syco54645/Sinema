Sinema.controller('CreateGrindhouseController', ['$scope', '$location', '$http', function($scope, $location, $http) {

  $scope.model = {
    step: 1,
    search: {
        selected: {
            genre: true,
            tag: true,
            prerolls: true,
            trailers: true,
        },
        options: {
          genreTagIntersect: false,
          genreMode: 'matchAny',
          tagMode: 'matchAny',
        },
        criteria: {
            //genre: [],
            genreId: [],
            //tag: [],
            tagId: [],
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
    assembledFeature: {},
    command: {},
    assembledFeatureId: 0,
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
      searchField: 'genre'
    },
    tag: {
      plugins: ['remove_button'],
      create: false,
      placeholder: 'Tags',
      options: viewVars.tags,
      valueField: 'id',
      labelField: 'tag',
      persist: false,
      searchField: 'tag'
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

  $scope.selectTag = function (tagSlug, tagId) {

    if ($scope.model.search.criteria.tag.indexOf(tagSlug) != -1) {
        var index = $scope.model.search.criteria.tag.indexOf(tagSlug);
        if (index !== -1) {
            $scope.model.search.criteria.tag.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.tag.push(tagSlug);
    }

    if ($scope.model.search.criteria.tagId.indexOf(tagId) != -1) {
        var index = $scope.model.search.criteria.tagId.indexOf(tagId);
        if (index !== -1) {
            $scope.model.search.criteria.tagId.splice(index, 1);
        }
    } else {
        $scope.model.search.criteria.tagId.push(tagId);
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

    promise.success(function(response) {
      if (response.status == "ok") {
        $scope.model.assembledFeature = JSON.parse(response.data.assembledFeature);
        viewVars.assembledFeature = $scope.model.assembledFeature;
        $scope.model.command = JSON.parse(response.data.command);
        $scope.model.assembledFeatureId = response.data.id;
        $scope.model.step = 2;
        var grindhouseId = response.data.id;
        window.location.href = "/admin/grindhouse/edit/" + grindhouseId;
      }
    });

    promise.error(function(response) {
      console.log(response);
    });
  }

  $scope.isSpoiler = function (type) {

    var isSpoiler = false;
    switch (type) {
        case 'Intro Preroll':
            isSpoiler = true;
            break;
        case 'Trailer':
            isSpoiler = true;
            break;
        case 'Feature Presentation Preroll':
            isSpoiler = true;
            break;
        case 'Film':
            isSpoiler = false;
            break;
        case 'Intermission Preroll':
            isSpoiler = true;
            break;
        case 'Joiner Preroll':
            isSpoiler = true;
            break;
        case 'Outro Preroll':
            isSpoiler = true;
        default:
            isSpoiler = true;
            break;
    }

    return isSpoiler;
  }

}]);
