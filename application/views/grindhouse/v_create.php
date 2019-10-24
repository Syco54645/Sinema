<script type="text/javascript" src="/js/controllers/creategrindhouse.js"></script>
<div ng-controller="CreateGrindhouseController">
    <p>This page will be used to create new double features.</p>

    <div class="row">
        <div class="col-md-12">

            <div ng-if="model.step==1">
                <div class="row">
                    <div class="col-md-4">
                        <div class="step">
                            <div class="step-number">Step 1</div>
                            <div class="step-desc">
                                Select the criteria to create a new feature with. Please note the more options you choose the harder it will be to create something.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">

                        <div class="form-group border">
                            <label class="control-label " for="genre-enable">
                                <input name="genre-enable" id="genre-enable" type="checkbox" ng-model="model.search.selected.genre" />
                                Genres
                            </label>
                            <div class="">
                                <label class="radio-inline">
                                    <input name="genreMode" type="radio" value="matchAny" ng-disabled ng-model="model.search.options.genreMode"="!model.search.selected.genre" />
                                    Match Any
                                </label>
                                <label class="radio-inline">
                                    <input name="genreMode" type="radio" value="matchEvery" ng-model="model.search.options.genreMode" ng-disabled="!model.search.selected.genre" />
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
                            <div class="">
                                <label class="radio-inline">
                                    <input name="genreSubgenreIntersect" type="radio" ng-value="true" ng-model="model.search.options.genreSubgenreIntersect" />
                                    And
                                </label>
                                <label class="radio-inline">
                                    <input name="genreSubgenreIntersect" type="radio" ng-value="false" ng-model="model.search.options.genreSubgenreIntersect" />
                                    Or
                                </label>
                            </div>
                            <span class="help-block">
                                Selecting "and" will cause a strict search with genre and tag
                            </span>
                        </div>

                        <div class="form-group border">
                            <label class="control-label " for="subgenre-enable">
                                <input name="subgenre-enable" id="subgenre-enable" type="checkbox" ng-model="model.search.selected.subgenre" />
                                Tags
                            </label>

                            <div class="">
                                <label class="radio-inline">
                                    <input name="subgenreMode" type="radio" value="matchAny" ng-model="model.search.options.subgenreMode" ng-disabled="!model.search.selected.subgenre" />
                                    Match Any
                                </label>
                                <label class="radio-inline">
                                    <input name="subgenreMode" type="radio" value="matchEvery" ng-model="model.search.options.subgenreMode" ng-disabled="!model.search.selected.subgenre" />
                                    Match All
                                </label>
                            </div>

                            <selectize
                                config="selectizeConfigs.subgenre"
                                id="subgenre"
                                name="subgenre"
                                ng-model="model.search.criteria.subgenreId"
                                ng-disabled="!model.search.selected.subgenre"
                            ></selectize>
                        </div>

                        <div class="form-group " ng-if="viewVars.sinemaSettings['enable-prerolls'] == '1'">
                            <label class="control-label " for="prerolls">
                                <input name="prerolls" id="prerolls" type="checkbox" ng-model="model.search.selected.prerolls" />
                                Prerolls
                            </label>
                            <div class="preroll-wrapper" ng-class="{ disabled: !model.search.selected.prerolls }">

                                <label class="control-label " for="stayInSeries">
                                    <input name="stayInSeries" id="stayInSeries" type="checkbox" ng-model="model.search.criteria.prerolls.stayInSeries" ng-disabled="!model.search.selected.prerolls" />
                                    Preferred Series
                                </label>

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
                            <div>
                                <button class="btn btn-primary" type="button" ng-click="createGrind(1)">
                                    Create
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- step 3 -->


        </div>
    </div>
     <pre>
        {{ model }}
    </pre>
</div>
<?php
