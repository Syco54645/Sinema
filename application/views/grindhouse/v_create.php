<script type="text/javascript" src="/js/controllers/creategrindhouse.js"></script>
<div class="create-grindhouse row" ng-controller="CreateGrindhouseController">
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
                <p>This page will be used to create new double features.</p>


                <div ng-if="model.step==1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="step">
                                <div class="step-number">Step 1</div>
                                <div class="step-desc">
                                    Select the criteria to create a new feature with. Please note the more options you choose the harder it will be to create something.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <div class="form-group border">
                                <input name="genre-enable" id="genre-enable" type="checkbox" ng-model="model.search.selected.genre" />
                                <label class="control-label " for="genre-enable">
                                    Genres
                                </label>
                                <div class="">
                                    <input name="genreMode" id="genreMode-any" type="radio" value="matchAny" ng-disabled ng-model="model.search.options.genreMode"="!model.search.selected.genre" />
                                    <label class="radio-inline" for="genreMode-any">
                                        Match Any
                                    </label>
                                    <input name="genreMode" id="genreMode-all" type="radio" value="matchEvery" ng-model="model.search.options.genreMode" ng-disabled="!model.search.selected.genre" />
                                    <label class="radio-inline" for="genreMode-all">
                                        Match All
                                    </label>
                                </div>
                                <selectize
                                    config="selectizeConfigs.genre"
                                    id="genre"
                                    name="genre"
                                    ng-model="model.search.criteria.genreId"
                                    ng-disabled="!model.search.selected.genre"
                                ></selectize>
                            </div>

                            <div class="form-group border">
                                <input name="genreTagIntersect" id="genreTagIntersect-and" type="radio" ng-value="true" ng-model="model.search.options.genreTagIntersect" />
                                <label class="radio-inline" for="genreTagIntersect-and">
                                    And
                                </label>
                                <input name="genreTagIntersect" id="genreTagIntersect-or" type="radio" ng-value="false" ng-model="model.search.options.genreTagIntersect" />
                                <label class="radio-inline" for="genreTagIntersect-or">
                                    Or
                                </label>
                                <div class="help-block">
                                    Selecting "and" will cause a strict search with genre and tag
                                </div>
                            </div>

                            <div class="form-group border">
                                <input name="tag-enable" id="tag-enable" type="checkbox" ng-model="model.search.selected.tag" />
                                <label class="control-label " for="tag-enable">
                                    Tags
                                </label>

                                <div class="">
                                    <input name="tagMode" id="tagMode-any" type="radio" value="matchAny" ng-model="model.search.options.tagMode" ng-disabled="!model.search.selected.tag" />
                                    <label class="radio-inline" for="tagMode-any">
                                        Match Any
                                    </label>
                                    <input name="tagMode" id="tagMode-all" type="radio" value="matchEvery" ng-model="model.search.options.tagMode" ng-disabled="!model.search.selected.tag" />
                                    <label class="radio-inline" for="tagMode-all">
                                        Match All
                                    </label>
                                </div>

                                <selectize
                                    config="selectizeConfigs.tag"
                                    id="tag"
                                    name="tag"
                                    ng-model="model.search.criteria.tagId"
                                    ng-disabled="!model.search.selected.tag"
                                ></selectize>
                            </div>

                            <div class="form-group " ng-if="viewVars.sinemaSettings['enable-prerolls'] == '1'">
                                <input name="prerolls" id="prerolls" type="checkbox" ng-model="model.search.selected.prerolls" />
                                <label class="control-label " for="prerolls">
                                    Prerolls
                                </label>
                                <div class="preroll-wrapper" ng-class="{ disabled: !model.search.selected.prerolls }">

                                    <input name="stayInSeries" id="stayInSeries" type="checkbox" ng-model="model.search.criteria.prerolls.stayInSeries" ng-disabled="!model.search.selected.prerolls" />
                                    <label class="control-label " for="stayInSeries">
                                        Preferred Series
                                    </label>
                                    <br>
                                    <label class="control-label " for="series">
                                       Series
                                    </label>
                                    <select class="select form-control"
                                        name="series"
                                        id="series"
                                        ng-options="prerollSeries.id as prerollSeries.preroll_series_name for prerollSeries in viewVars.prerollSeries track by prerollSeries.id"
                                        ng-model="model.search.criteria.prerolls.selectedSeries"
                                        ng-disabled="!model.search.selected.prerolls || !model.search.criteria.prerolls.stayInSeries"
                                    ></select>

                                </div>
                            </div>


                            <div class="form-group">
                                <button class="btn btn-primary" type="button" ng-click="createGrind(1)">
                                    Create
                                </button>
                            </div>

                        </div>
                    </div>
                </div><!-- step 1 -->

                <div ng-if="model.step==2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="step">
                                <div class="step-number">Step 2</div>
                                <div class="step-desc">
                                    This shows a preview of the feature that you created. If you approve of this feature click "Create Plex Playlist" to create a playlist.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php $this->view('/partials/grindhouse-items'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
