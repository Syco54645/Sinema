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
                        
                        <div class="form-group ">
                            <label class="control-label " for="genre">
                                <input name="genre" id="genre" type="checkbox" ng-change="toggleGenre()" ng-model="model.search.selected.genre" />
                                Genre
                            </label>
                            <div class="genre-wrapper" ng-class="{ disabled: !model.search.selected.genre }" >
                                
                                <div class="row" ng-repeat="genres in viewVars.genres | chunkBy:4">
                                    <div class="col-md-3 letter-box" ng-repeat="genre in genres" >
                                        <label class="checkbox genre-checkbox">
                                            <input name="checkbox" type="checkbox" ng-change="selectGenre(genre.genre_slug, genre.id)" ng-model="genre.isChecked" value="{{ genre.genre_slug }}" ng-disabled="!model.search.selected.genre" />
                                            {{ genre.genre }}
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="control-label " for="subgenre">
                                <input name="subgenre" id="subgenre" type="checkbox" ng-change="toggleGenre()" ng-model="model.search.selected.subgenre" />
                                Subgenre
                            </label>
                            <div class="subgenre-wrapper" ng-class="{ disabled: !model.search.selected.subgenre }" >
                                <div class="row" ng-repeat="subgenres in viewVars.subgenres | chunkBy:4">
                                    <div class="col-md-3 letter-box" ng-repeat="subgenre in subgenres" >
                                        <label class="checkbox subgenre-checkbox">
                                            <input name="checkbox" type="checkbox" ng-change="selectSubgenre(subgenre.subgenre_slug, subgenre.id)" ng-model="subgenre.isChecked" value="{{ subgenre.subgenre_slug }}" ng-disabled="!model.search.selected.subgenre" />
                                            {{ subgenre.subgenre }}
                                        </label>
                                    </div>
                                </div>

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
