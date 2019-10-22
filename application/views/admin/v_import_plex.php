<script type="text/javascript" src="/js/controllers/importplex.js"></script>
<div ng-controller="ImportPlexController">
    <p>This page will be used to populate the database with the items in your plex server.</p>

    <div id="plexLibraryModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plex Libraries</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Library</td>
                        </tr>

                        <tr class="plex-library-item" ng-click="clickPlexLibrary(library.id, library.title)" ng-repeat="library in viewVars.formattedLibraries">
                            <td>{{ library.id }}</td>
                            <td>{{ library.title }}</td>
                        </tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">


            <div ng-if="model.step == 1">
                <div class="row">
                    <div class="col-md-4">
                        <div class="step">
                            <div class="step-number">Step 1</div>
                            <div class="step-desc">
                                This step is fairly simple. We are going to use the Plex Access Token that you supplied to connect to your Plex library.
                                We will then begin to pull in the items in the specified library and store information needed to create the features.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group" id="div_processingMode">
                            <label class="control-label " for="processingMode">
                                Import or Update library
                            </label>
                            <div class="">
                                <label class="radio-inline">
                                    <input name="processingMode" ng-model="model.processingMode" type="radio" value="import" />
                                    Import New
                                </label>
                                <label class="radio-inline">
                                    <input name="processingMode" ng-model="model.processingMode" type="radio" value="update" />
                                    Update Existing
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">

                        <div class="update-mode-wrapper" ng-if="model.processingMode == 'update'">
                            <div class="form-group">
                                <label class="control-label " for="type">
                                   Type
                                </label>
                                <select class="select form-control"
                                    name="type"
                                    id="libraryId"
                                    ng-options="library.id as library.library_name for library in viewVars.libraries"
                                    ng-model="model.libraryId"
                                    ng-change="selectLibrary()"
                                ></select>
                            </div>
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary" type="button" ng-click="importMovie(1)" ng-disabled="model.importing">
                                        Update Movies
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="import-mode-wrapper" ng-if="model.processingMode == 'import'">
                            <div class="form-group ">
                                <label class="control-label" for="import-type">Type</label>
                                <select class="form-control" name="import-type" id="import-type" ng-model="model.importType">
                                    <option>--------</option>
                                    <option value="movie">Movies</option>
                                    <option value="preroll" ng-if="viewVars.sinemaSettings['enable-prerolls'] == '1'">Prerolls</option>
                                    <option value="trailer" ng-if="viewVars.sinemaSettings['enable-trailers'] == '1'">Trailers</option>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="plex-library-alias">Library Alias</label>
                                <input class="form-control" id="plex-library-alias" name="plex-library-alias" type="text" ng-model="model.libraryAlias" />
                                <div class="help-block"></div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="plex-library-id">Plex Movie Library ID</label>
                                <input class="form-control" id="plex-library-id" name="plex-library-id" type="text" ng-model="model.libraryId" />
                                <div class="help-block">If you are unsure what your library ID is just click the button.</div>
                                <button type="button" class="btn btn-green micro" data-toggle="modal" data-target="#plexLibraryModal">
                                    Show Plex Libraries
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary" type="button" ng-click="importMovie(1)" ng-disabled="model.importing">
                                        Import/Update Movies
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- step 1 -->

            <div ng-if="model.step == 2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="step">
                            <div class="step-number">Step 2</div>
                            <div class="step-desc">
                                This step is more complex. We have to pull subgenre from IMDB via an API. We can batch this in about 30 at a time safely.
                                You can tweak this number in settings but eventually you will get to a point where the script fails to run.
                                The application will continue to the next step automatically.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <div>
                                <button class="btn btn-primary" type="button" ng-click="importMovie(2)" ng-disabled="model.importing">
                                    Import/Update Subgenres
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- step 2 -->


            <div ng-if="model.step == 3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="step">
                            <div class="step-number">Step 3</div>
                            <div class="step-desc">
                                You should be good to go with this library. Now go create a double feature!
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- step 3 -->


        </div>
    </div>
     <pre ng-if="false">
        {{ model }}
    </pre>
</div>
<?php
