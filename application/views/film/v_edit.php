<script type="text/javascript" src="/js/controllers/editfilm.js"></script>
<div class="edit-film" ng-controller="EditFilmController">
    <?php echo $this->session->flashdata('success'); ?>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">

            <div class="checkbox">
                <label class="checkbox">
                    <input name="checkbox" type="checkbox" ng-model="model.active" ng-change="changeActive()" />
                    Active
                </label>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="control-label " for="title">
                        Title
                    </label>
                    <input class="form-control"  type="text" name="title" id="title" ng-model="model.film.title" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="control-label " for="description">
                        Description
                    </label>
                    <textarea class="form-control" cols="40" id="description" name="description" rows="10" ng-model="model.film.summary"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <button class="btn btn-primary" type="button" ng-click="saveFilm()">
                        Save
                    </button>
                </div>
            </div>

        </div>
        
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    <img class="film-image" src="{{ ::viewVars.film.thumbUrl }}" />
                </div>
            </div>
        </div>
    </div>    

</div>
<?php 
