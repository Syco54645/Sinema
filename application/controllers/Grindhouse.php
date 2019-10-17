<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grindhouse extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');
        if ($this->starterData['currentRoute'] == 'grindhouse-create') {
            if (!$this->auth->isAuthed()) {
                redirect('/admin/login');
            }
        }
        $this->load->model('FilmModel', 'filmmodel');
    }

    public function index() {

        $data = $this->starterData;

        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_index');
        $this->load->view('partials/template-footer', $data);
    }

    public function create() {

        $data = $this->starterData;

        $data['genres'] = $this->filmmodel->getGenres();
        $data['subgenres'] = $this->filmmodel->getSubgenres();        

        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_create');
        $this->load->view('partials/template-footer', $data);

    }

    public function ajaxCreate() {
        $films = [];

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $filmIds = $this->filmmodel->featureSearch($this->input->post('search'));
            
            // get random position in the array
            $film1Key = rand(0, count($filmIds) -1 );
            do {
                $film2Key = rand(0, count($filmIds) -1);
            } while ($film1Key == $film2Key);

            $film1 = $this->filmmodel->getFilmById($filmIds[$film1Key]);
            $film2 = $this->filmmodel->getFilmById($filmIds[$film2Key]);
Utility::debug($film1, false);
Utility::debug($film2, false);
            echo $film1Key . " " . $film2Key;
            Utility::debug($filmIds, true);
        }
    }

    public function upcoming() {

    }

    public function past() {

    }

    public function calendar() {

    }

}
