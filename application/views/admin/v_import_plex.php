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
                        <?php foreach ($formattedLibraries as $library): ?>
                            <tr>
                                <td><?php echo $library['id'] ?></td>
                                <td><?php echo $library['title'] ?></td>
                            </tr>
                        <?php endforeach ?>
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
                            <label class="control-label" for="plex-movie-location-id">Plex Movie Library ID</label>
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
