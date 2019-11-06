<script type="text/javascript" src="/js/vendor/datetimepicker.js"></script>
<script type="text/javascript" src="/js/vendor/datetimepicker.templates.js"></script>
<script type="text/javascript" src="/js/vendor/dateTimeInput.js"></script>
<link rel="stylesheet" href="/css/vendor/datetimepicker.css">
<script type="text/javascript" src="/js/controllers/editgrindhouse.js"></script>
<div class="edit-grindhouse row" ng-controller="EditGrindhouseController">
    <div class="col-md-6">
        <div class="flash-data">
            <flash-message></flash-message>
        </div>
        <?php echo $this->session->flashdata('success'); ?>
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label " for="title">
                        Title
                    </label>
                    <input class="form-control"  type="text" name="title" id="title" ng-model="model.grindhouse.title" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label " for="tagline">
                        Tagline
                    </label>
                    <input class="form-control"  type="text" name="tagline" id="tagline" ng-model="model.grindhouse.tagline" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label " for="calendar-dropdown">
                        Show Time
                    </label>
                    <div class="calendar-dropdown">
                        <a class="dropdown-toggle" id="calendar-dropdown" role="button" data-toggle="dropdown" data-target=".calendar-dropdown" href="#">
                            <div class="input-group mb-3">
                                <input type="text" id="date" data-date-time-input="YYYY-MM-DD h:mm a" name="date" class="form-control" data-ng-model="model.grindhouse.showDate">
                                <span class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <datetimepicker data-ng-model="model.grindhouse.showDate" data-datetimepicker-config="{ dropdownSelector: '#calendar-dropdown' }"></datetimepicker>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div>
                        <button class="btn btn-primary" type="button" ng-click="saveGrindhouse()">
                            Save
                        </button>
                        <button class="btn btn-primary" type="button" ng-click="createPlexPlaylist()" ng-disabled="model.creatingPlexPlaylist">
                            Create Plex Playlist
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                Reserved for poster generation
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $this->view('/partials/grindhouse-items'); ?>
            </div>
        </div>
    </div>
</div>
<?php
