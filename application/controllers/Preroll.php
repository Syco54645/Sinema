<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preroll extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');

        /*if ($this->starterData['currentRoute'] == 'preroll-manage') {
        }*/
        if (!$this->auth->isAuthed()) {
            redirect('/admin/login');
        }
        $this->load->model('FilmModel', 'filmmodel');
        $this->load->model('PrerollModel', 'prerollmodel');
    }

    public function manage() {
        $data = $this->starterData;

        $seriesId = $this->input->get('seriesId');
        $typeId = $this->input->get('typeId');

        $options = [
            'seriesId' => $seriesId,
            'typeId' => $typeId,
        ];

        $data['prerolls'] = $this->prerollmodel->getPrerolls($options);

        $data['title'] = "Prerolls (" . count($data['prerolls']) . ")";

        $this->load->view('partials/template-header', $data);
        $this->load->view('preroll/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($prerollId) {
        $data = $this->starterData;

        $data['preroll'] = $this->prerollmodel->getPrerollById($prerollId);
        $data['prerollTypes'] = $this->prerollmodel->getPrerollTypes();
        $data['prerollSeries'] = $this->prerollmodel->getPrerollSeries();

        $this->load->view('partials/template-header', $data);
        $this->load->view('preroll/v_edit');
        $this->load->view('partials/template-footer', $data);
    }

    public function ajaxSave() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $qd = $_POST;
            $id = $this->input->post('id');
            unset($qd['id']);

            $this->prerollmodel->updatePreroll($id, $qd);

            $response = [];
            $jsonResponse = new JsonResponse();

            echo $jsonResponse->create('ok', '', $response);
        }
    }
}
