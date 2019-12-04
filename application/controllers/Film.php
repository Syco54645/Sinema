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
        $tagId = $this->input->get('tagId');

        $options = [
            'genreId' => $genreId,
            'tagId' => $tagId,
            'orderBy' => ['field' => 'title', 'direction' => 'asc'],
        ];

        $data['films'] = $this->filmmodel->getFilms($options);

        foreach ($data['films'] as &$film) {
            $genres = $this->filmmodel->getGenresForFilm($film['id']);
            $film['genres'] = $genres;

            $tags = $this->filmmodel->getTagsForFilm($film['id']);
            $film['tags'] = $tags;
        }

        $data['title'] = "Films (" . count($data['films']) . ")";

        $this->load->view('partials/template-header', $data);
        $this->load->view('film/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function create() {
        $data = $this->starterData;

        $data['film'] = [];

        $data['title'] = "Create Film";

        $data['libraries'] = $this->filmmodel->getLibraryAliases();

        $this->load->view('partials/template-header', $data);
        $this->load->view('film/v_edit');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($filmId) {
        $data = $this->starterData;

        $data['film'] = $this->filmmodel->getFilmById($filmId);

        $data['title'] = "Edit Film - " . $data['film']['title'];

        $data['libraries'] = $this->filmmodel->getLibraryAliases();

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

            $response = [];
            if ($id == null) {
                $filmId = $this->filmmodel->storeFilm($qd);
                $response['film_id'] = $filmId;
            } else {
                $this->filmmodel->updateFilm($id, $qd);
            }

            $jsonResponse = new JsonResponse();

            echo $jsonResponse->create('ok', '', $response);
        }
    }
}
