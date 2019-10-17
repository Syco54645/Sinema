<script type="text/javascript" src="/js/controllers/editpreroll.js"></script>
<div class="edit-preoll" ng-controller="EditPrerollController">
    <?php echo $this->session->flashdata('success'); ?>
    <div class="row">
        <div class="col-md-12">
            
            <div class="checkbox">
                <label class="checkbox">
                    <input name="checkbox" type="checkbox" ng-model="model.active" ng-change="changeActive()" />
                    Active
                </label>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
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
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label " for="title">
                        Title
                    </label>
                    <input class="form-control"  type="text" name="title" id="title" ng-model="model.preroll.title" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label " for="description">
                        Description
                    </label>
                    <textarea class="form-control" cols="40" id="description" name="description" rows="10" ng-model="model.preroll.summary"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <img class="preroll-image" src="{{ ::viewVars.preroll.thumbUrl }}" />
                </div>
            </div>

            <div class="form-group">
                <div>
                    <button class="btn btn-primary" type="button" ng-click="savePreroll()">
                        Save
                    </button>
                </div>
            </div>

        </div>
    </div>    
{{model.preroll}}
</div>
<?php 
//Utility::debug($preroll, true);