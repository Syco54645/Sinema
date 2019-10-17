<div class="row">
    <div class="col-md-12">
        <form class="form-settings" method="POST" action="/admin/settings">

            <?php foreach ($settings as $k=>$setting): ?>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label
                                class="control-label"
                                for="<?php echo $setting['setting_slug'] ?>"
                                >
                                    <?php echo $setting['setting_name'] ?>
                                </label>
                            <input
                                class="form-control "
                                id="<?php echo $setting['setting_slug'] ?>"
                                name="<?php echo $setting['setting_slug'] ?>"
                                type="text"
                                value="<?php echo $setting['setting_value'] ?>"
                            />
                            <span class="help-block"><?php echo $setting['description'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary" type="submit">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>