<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trailer extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');

        if (!$this->auth->isAuthed()) {
            redirect('/admin/login');
        }
        $this->load->model('FilmModel', 'filmmodel');
        $this->load->model('TrailerModel', 'trailermodel');
    }

    public function manage() {
        $data = $this->starterData;

        $seriesId = $this->input->get('seriesId');
        $typeId = $this->input->get('typeId');

        $options = [
            'seriesId' => $seriesId,
            'typeId' => $typeId,
        ];

        $data['trailers'] = $this->trailermodel->getTrailers($options);

        $data['title'] = "Trailers (" . count($data['trailers']) . ")";

        $this->load->view('partials/template-header', $data);
        $this->load->view('trailer/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($trailerId) {
        $data = $this->starterData;

        $data['trailer'] = $this->trailermodel->getTrailerById($trailerId);

        $this->load->view('partials/template-header', $data);
        $this->load->view('trailer/v_edit');
        $this->load->view('partials/template-footer', $data);
    }

    public function ajaxSave() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $qd = $_POST;
            $id = $this->input->post('id');
            unset($qd['id']);

            $this->trailermodel->updateTrailer($id, $qd);

            $response = [];
            $jsonResponse = new JsonResponse();

            echo $jsonResponse->create('ok', '', $response);
        }
    }
}
