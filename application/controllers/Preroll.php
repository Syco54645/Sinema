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
    }

    public function manage() {
        $data = $this->starterData;

        $data['prerolls'] = $this->filmmodel->getPrerolls();
        
        $this->load->view('partials/template-header', $data);
        $this->load->view('preroll/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($prerollId) {
        $data = $this->starterData;

        $data['preroll'] = $this->filmmodel->getPrerollById($prerollId);
        $data['prerollTypes'] = $this->filmmodel->getPrerollTypes();

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
            
            $this->filmmodel->updatePreroll($id, $qd);

            $this->session->set_flashdata('success', 'Preroll Updated Successfully');
            echo json_encode(['status' => 'success']);
        }
    }
}
