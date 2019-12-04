<script type="text/javascript" src="/js/controllers/editpreroll.js"></script>
<div class="edit-preroll row" ng-controller="EditPrerollController">
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
                            <label class="control-label " for="type">
                               Type
                            </label>
                            <select class="select form-control"
                                name="type"
                                id="type"
                                ng-options="prerollType.id as prerollType.preroll_type_name for prerollType in viewVars.prerollTypes"

                                ng-model="model.preroll.preroll_type_id"
                            ></select>
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="series">
                               Series
                            </label>
                            <select class="select form-control"
                                name="series"
                                id="series"
                                ng-options="prerollSeries.id as prerollSeries.preroll_series_name for prerollSeries in viewVars.prerollSeries"

                                ng-model="model.preroll.preroll_series_id"
                            ></select>
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="title">
                                Title
                            </label>
                            <input class="form-control"  type="text" name="title" id="title" ng-model="model.preroll.title" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="description">
                                Summary
                            </label>
                            <textarea class="form-control" cols="40" id="description" name="description" rows="10" ng-model="model.preroll.summary"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="library">
                               Library
                            </label>
                            <select class="select form-control"
                                name="library"
                                id="libraryId"
                                ng-options="library.id as library.library_name for library in viewVars.libraries"
                                ng-model="model.preroll.library_id"
                            >
                                <option value="">--------</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary float-right" type="button" ng-click="savePreroll()">
                                Save
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <img class="preroll-image" src="{{ ::viewVars.preroll.thumbUrl }}" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
