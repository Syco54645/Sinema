<script type="text/javascript" src="/js/controllers/exportplex.js"></script>
<div ng-controller="ExportPlexController">
    <p>This page will be used to export media from your plex server. It will create a csv file that you can feed to archive.org for ease of uploading.</p>

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
                    <p>Click on a library to fill out the form.</p>
                    <div class="plex-library-wrapper">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Library</th>
                            </tr>

                            <tr class="plex-library-item" ng-click="clickPlexLibrary(library.id, library.title)" ng-repeat="library in viewVars.formattedLibraries">
                                <td>{{ library.id }}</td>
                                <td>{{ library.title }}</td>
                            </tr>
                        </table>
                    </div>
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
                                Select the library that you want to export.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">

                        <div class="import-mode-wrapper">
                            <div class="form-group ">
                                <label class="control-label" for="type">Type</label>
                                <select class="form-control" name="type" id="type" ng-model="model.libraryType">
                                    <option>--------</option>
                                    <option value="movie">Movies</option>
                                    <option value="preroll" ng-if="viewVars.sinemaSettings['enable-prerolls'] == '1'">Prerolls</option>
                                    <option value="trailer" ng-if="viewVars.sinemaSettings['enable-trailers'] == '1'">Trailers</option>
                                </select>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="plex-library-id">Plex Movie Library ID</label>
                                <input class="form-control" id="plex-library-id" name="plex-library-id" type="text" ng-model="model.libraryId" />
                                <div class="help-block">If you are unsure what your library ID is just click the button.</div>
                                <button type="button" class="btn btn-green micro" data-toggle="modal" data-target="#plexLibraryModal">
                                    Show Plex Libraries
                                </button>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="identifier-prefix">Identifer Prefix</label>
                                <input class="form-control" id="identifier-prefix" name="identifier-prefix" type="text" ng-model="model.identifierPrefix" />
                                <div class="help-block">Try to keep this short. Identifiers can only be 50 characters total.</div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="collection-name">Collection Name</label>
                                <input class="form-control" id="collection-name" name="collection-name" type="text" ng-model="model.collectionName" />
                                <div class="help-block">Collection you are allowed to upload to on archive.org. Leave default if you are unsure.</div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label" for="export-type">Export Type</label>
                                <select class="form-control" name="export-type" id="export-type" ng-model="model.exportType">
                                    <option value="file">File</option>
                                    <option value="metadata">Metadata</option>
                                </select>
                                <div class="help-block">Choose file if you are uploading new file. Choose metadata if you are updating metadata.</div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary" type="button" ng-click="export(1)" ng-disabled="model.exporting">
                                        Generate CSV
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- step 1 -->



        </div>
    </div>
    <textarea ng-model="model.csv"></textarea>
</div>
<?php
