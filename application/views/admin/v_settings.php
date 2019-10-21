<script type="text/javascript" src="/js/controllers/settings.js"></script>
<div class="row" ng-controller="SettingsController">
    <div class="col-md-12">
        <div class="flash-data">
            <flash-message></flash-message>
        </div>
        <div class="row" ng-repeat="setting in viewVars.settings">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label
                        class="control-label"
                        for="{{ setting.setting_slug }}"
                        >
                            <input
                                name="{{ setting.setting_slug }}"
                                type="checkbox"
                                ng-model="model.settings[setting.setting_slug]"
                                ng-true-value="'1'"
                                ng-false-value="'0'"
                                id="{{ setting.setting_slug }}"
                                ng-if="setting.widget_type == 'checkbox'"
                            />
                            {{ setting.setting_name }}
                        </label>
                    <input
                        class="form-control "
                        id="{{ setting.setting_slug }}"
                        name="{{ setting.setting_slug }}"
                        type="text"
                        ng-model="model.settings[setting.setting_slug]"
                        ng-if="setting.widget_type == 'text'"
                    />
                    <selectize
                        config="selectizeConfigs[setting.setting_slug]"
                        id="{{ setting.setting_slug }}"
                        name="{{ setting.setting_slug }}"
                        ng-model="selectizeModels[setting.setting_slug]"
                        ng-if="setting.widget_type == 'selectize'"
                    ></selectize>

                    <p>
                        <span class="help-block">{{ setting.description }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group">
                    <div>
                        <button class="btn btn-primary" type="button" ng-click="save()">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
