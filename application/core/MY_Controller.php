<?php
class MY_Controller extends CI_Controller {

    public $starterData = [];

    public function __construct() {
        parent::__construct();
        $this->starterData['me'] = $this->session->userdata;
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        $this->starterData['currentRoute'] = $controller . "-" . $method;
    }
}