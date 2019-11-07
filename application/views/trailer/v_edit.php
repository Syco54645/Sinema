<script type="text/javascript" src="/js/controllers/edittrailer.js"></script>
<div class="edit-trailer row" ng-controller="EditTrailerController">
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
                            <input class="form-control"  type="text" name="title" id="title" ng-model="model.trailer.title" />
                        </div>

                        <div class="form-group">
                            <label class="control-label " for="description">
                                Description
                            </label>
                            <textarea class="form-control" cols="40" id="description" name="description" rows="10" ng-model="model.trailer.summary"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary float-right" type="button" ng-click="saveTrailer()">
                                Save
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="row">
                            <div class="col-md-12">
                                <img class="trailer-image" src="{{ ::viewVars.trailer.thumbUrl }}" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
