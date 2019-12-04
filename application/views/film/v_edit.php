<script type="text/javascript" src="/js/controllers/editfilm.js"></script>
<div class="edit-film row" ng-controller="EditFilmController">
    <div class="col-md-12">
        <div class="flash-data">
            <flash-message></flash-message>
        </div>
        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">{{ ::pageTitle() }}</h4>
                <p class="card-category" ng-if="::pageSubtitle()">{{ ::pageSubtitle() }}</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <div class="checkbox">
                                <input name="checkbox" type="checkbox" ng-model="model.active" ng-change="changeActive()" />
                                <label class="checkbox">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="title">
                                Title
                            </label>
                            <input class="form-control"  type="text" name="title" id="title" ng-model="model.film.title" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="year">
                                Year
                            </label>
                            <input class="form-control" type="text" name="year" id="year" ng-model="model.film.year" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="description">
                                Summary
                            </label>
                            <textarea class="form-control" cols="40" id="description" name="description" rows="10" ng-model="model.film.summary"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="studio">
                                Studio
                            </label>
                            <input class="form-control" type="text" name="studio" id="studio" ng-model="model.film.studio" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="rating">
                                Rating
                            </label>
                            <input class="form-control" type="text" name="rating" id="rating" ng-model="model.film.rating" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="library">
                               Library
                            </label>
                            <select class="select form-control"
                                name="library"
                                id="libraryId"
                                ng-options="library.id as library.library_name for library in viewVars.libraries"
                                ng-model="model.film.library_id"
                                ng-change="selectLibrary()"
                            >
                                <option value="">--------</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div>
                                <button class="btn btn-primary float-right" type="button" ng-click="saveFilm()">
                                    Save
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <img class="film-image" src="{{ ::viewVars.film.thumbUrl }}" />
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
