<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Film extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');

        /*if ($this->starterData['currentRoute'] == 'preroll-manage') {
        }*/
        if (!$this->auth->isAuthed()) {
            redirect('/admin/login');
        }
        $this->load->model('FilmModel', 'filmmodel');
    }

    public function manage() {
        $data = $this->starterData;

        $genreId = $this->input->get('genreId');
        $subgenreId = $this->input->get('subgenreId');

        $options = [
            'genreId' => $genreId,
            'subgenreId' => $subgenreId,
            'orderBy' => ['field' => 'title', 'direction' => 'asc'],
        ];

        $data['films'] = $this->filmmodel->getFilms($options);

        foreach ($data['films'] as &$film) {
            $genres = $this->filmmodel->getGenresForFilm($film['id']);
            $film['genres'] = $genres;

            $subgenres = $this->filmmodel->getSubgenresForFilm($film['id']);
            $film['subgenres'] = $subgenres;
        }

        $data['title'] = "Films (" . count($data['films']) . ")";

        $this->load->view('partials/template-header', $data);
        $this->load->view('film/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($filmId) {
        $data = $this->starterData;

        $data['film'] = $this->filmmodel->getFilmById($filmId);


        $this->load->view('partials/template-header', $data);
        $this->load->view('film/v_edit');
        $this->load->view('partials/template-footer', $data);
    }

    public function ajaxSave() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $qd = $_POST;
            $id = $this->input->post('id');
            unset($qd['id']);
            
            $this->filmmodel->updatePreroll($id, $qd);

            $this->session->set_flashdata('success', 'Preroll Updated Successfully');
            echo json_encode(['status' => 'success']);
        }
    }
}
