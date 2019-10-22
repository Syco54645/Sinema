<?php
class MY_Controller extends CI_Controller {

    public $starterData = [];
    public $cinemaSettings = [];

    public function __construct() {
        parent::__construct();
        $this->starterData['me'] = $this->session->userdata;
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        $this->starterData['currentRoute'] = $controller . "-" . $method;
        $this->load->model('SettingsModel', 'settingsmodel');
        if (empty($this->config->sinemaApplicationSettings)) {
            $this->config->sinemaApplicationSettings = $this->settingsmodel->getSettings();
        }

        $sinemaSettings = $this->updateSinemaSettings();
        $this->starterData['sinemaSettings'] = $sinemaSettings['viewSafeSinemaSettings'];
        $this->config->sinemaSettings = $sinemaSettings['allSinemaSettings'];
    }

    public static function updateSinemaSettings($forView = false) {
        $settings = Utility::getSettings();
        $allSinemaSettings = [];
        $viewSafeSinemaSettings = [];
        // should really do this better but meh FIXME
        foreach ($settings as $settingSlug => $settingValue) {
            if (!in_array($settingSlug, ['plex-api-token', 'plex-url'])) { // dont expose the api token to the world
                $viewSafeSinemaSettings[$settingSlug] = $settingValue;
            }
            $allSinemaSettings[$settingSlug] = $settingValue;
        }
        return [
            'allSinemaSettings' => $allSinemaSettings,
            'viewSafeSinemaSettings' => $viewSafeSinemaSettings,
        ];
    }
}
