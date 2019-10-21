<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public $plexApiToken = null;

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');
        if ($this->uri->segment(2) !== 'login') {
            if (!$this->auth->isAuthed()) {
                redirect('/admin/login');
            }
        }
        $this->load->model('FilmModel', 'filmmodel');
        $this->load->model('SettingsModel', 'settingsmodel');

        $this->plexApiToken = $this->settingsmodel->getSettingBySlug('plex-api-token');
    }

    private function adminData() {
        $data = $this->starterData;
        return $data;
    }

    public function index() {

        $data = self::adminData();
        $data['title'] = 'Admin';

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_index');
        $this->load->view('partials/template-footer', $data);
    }

    public function junk() {
        echo "<h1>general dumping ground</h1>";
    }

    public function login() {

        $data = self::adminData();

        if ($this->auth->isAuthed()) {
            redirect('/admin');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $this->auth->loginUser($username, $password);

            if ($user !== null){
                $this->session->set_userdata($user);
                $this->session->set_userdata('login', 'true');
                $this->session->set_flashdata('login_info', 'Login Successful');
                redirect('/admin');
            } else {
                $this->session->set_flashdata('login_info', 'Invalid username or password');
            }
        }

        $this->load->view('partials/template-header', $data);
        $this->load->view('user/v_login');
        $this->load->view('partials/template-footer', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/admin');
    }

    public function settings() {
        $data = self::adminData();

        $data['title'] = "Settings";

        $settings = $this->settingsmodel->getSettings();
        foreach ($settings as $i => $setting) {
            if ($setting['setting_slug'] == 'kept-subgenres') {
                $keptSubgenres = $settings[$i]['setting_value'];
                $keptSubgenresArray = array_map('trim', explode(';', $keptSubgenres));
                $settings[$i]['setting_value'] = $keptSubgenresArray;
                $data['keptSubgenresArray'] = $keptSubgenresArray;
            }
        }
        $data['settings'] = $settings;

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_settings', $data);
        $this->load->view('partials/template-footer', $data);
    }

    public function ajaxSaveSettings() {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            foreach($_POST['settings'] as $setting => $settingValue) {
                $this->settingsmodel->saveSetting($setting, $settingValue);
            }
            $this->session->set_flashdata('success', 'Settings Updated Successfully');
            $sinemaSettings = $this->updateSinemaSettings();
            echo json_encode(['status' => 'success', 'sinemaSettings' => $sinemaSettings]);
        }
    }



    /*public function do_import_plex($step=1) {

        $data = self::adminData();
        $data['title'] = "Import Collection From Plex";

        $type = $this->input->post('type');
        $data['import'] = [
            'type' => $type,
            'step' => $step,
        ];

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_do_import_plex', $data);
        $this->load->view('partials/template-footer', $data);
    }*/

    function styff() {

        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        //$json = Utility::convertXmlToJson($get_data);
        //$array = json_decode($json, TRUE);
        $array = json_decode($get_data, TRUE);
        //Utility::debug($array, true);

        //Utility::debug($array['MediaContainer']["Metadata"], true);

        $allSubGenre = [];

        foreach($array['MediaContainer']["Metadata"] as $k=>$video) {
            $movie = [];
            $movie['id'] = isset($video['ratingKey']) ? $video['ratingKey'] : null;
            $movie['title'] = isset($video['title']) ? $video['title'] : null;
            $movie['studio'] = isset($video['studio']) ? $video['studio'] : null;
            $movie['rating'] = isset($video['rating']) ? $video['rating'] : null;
            $movie['year'] = isset($video['year']) ? $video['year'] : null;
            $movie['summary'] = isset($video['summary']) ? $video['summary'] : null;
            $movie['thumb'] = isset($video['thumb']) ? $video['thumb'] : null;
            $movie['art'] = isset($video['art']) ? $video['art'] : null;
            $movie['guid'] = isset($video['guid']) ? $video['guid'] : null;
            if (isset($video['guid'])) {
                $movie['imdbId'] = Utility::getImdbId($video['guid']);
            } else {
                $movie['imdbId'] = null;
            }

            $movie['country'] = null;
            if (isset($video['Country'])) {
                $movie['country'] = [];
                foreach ($video['Country'] as $country) {
                    $movie['country'][] = $country['tag'];
                }
            }

            $movie['genre'] = null;
            if (isset($video['Genre'])) {
                $movie['genre'] = [];
                foreach ($video['Genre'] as $genre) {
                    $movie['genre'][] = $genre['tag'];
                }
            }
            Utility::debug($movie, false);

            $movie['thumbUrl'] = Utility::getPlexThumbnailUrl($movie['thumb'], $this->plexApiToken);
            $movie['artUrl'] = Utility::getPlexThumbnailUrl($movie['art'], $this->plexApiToken);
            echo '<img src="' . $movie['thumbUrl'] . '" />';
            echo '<img src="' . $movie['artUrl'] . '" />';
            $qd = [
                'id' => $movie['id'],
                'title' => $movie['title'],
                'studio' => $movie['studio'],
                'rating' => $movie['rating'],
                'year' => $movie['year'],
                'summary' => $movie['summary'],
                'thumb' => $movie['thumb'],
                'art' => $movie['art'],
                'guid' => $movie['guid'],
                'imdbId' => $movie['imdbId'],
                'thumbUrl' => $movie['thumbUrl'],
                'artUrl' => $movie['artUrl'],
            ];

            /*Utility::debug($this->filmmodel->getFilmById($movie['id'])->num_rows(), false);
            Utility::debug($this->filmmodel->getFilmById($movie['id'])->result_array(), false);
            Utility::debug($this->filmmodel->checkFilmExists($movie['id']), false);
            Utility::debug($movie['id'], false);*/

            // insert the film if it doesnt exist
            if ($this->filmmodel->checkFilmExists($movie['id']) === 0) {

                //echo "here we are!!!!!!!!!!!!!!";
                $this->filmmodel->storeFilm($qd);
            }

            foreach ($movie['genre'] as $genre) {
                $qd = [
                    "genre" => $genre,
                    "genre_slug" => Utility::slugify($genre),
                ];

                $genreId = isset($this->filmmodel->getGenreBySlug($qd['genre_slug'])->id) ? $this->filmmodel->getGenreBySlug($qd['genre_slug'])->id : null;

                if (!$genreId) {
                    $genreId = $this->filmmodel->storeGenre($qd);
                }

                $this->filmmodel->mapGenreToFilm($movie['id'], $genreId);
            }

            $movieSubgenres = Utility::getMovieSubgenres($movie['imdbId']);
            foreach ($movieSubgenres as $subgenre) {
                $qd = [
                    "subgenre" => $subgenre,
                    "subgenre_slug" => Utility::slugify($subgenre),
                ];

                $subgenreId = isset($this->filmmodel->getSubgenreBySlug($qd['subgenre_slug'])->id) ? $this->filmmodel->getSubgenreBySlug($qd['subgenre_slug'])->id : null;

                if (!$subgenreId) {
                    $subgenreId = $this->filmmodel->storeSubgenre($qd);
                }

                $this->filmmodel->mapSubgenreToFilm($movie['id'], $subgenreId);
            }
            Utility::debug($movieSubgenres, true);


            Utility::debug($movie['genre'], true);

            $command = escapeshellcmd('python ./test.py ' . str_replace('tt', '', $movie['imdbId']));
            $output = shell_exec($command);
            $movieSubgenres = Utility::getWantedSubgenres(json_decode($output));
            Utility::debug(json_decode($output), false);
            if (json_decode($output)) {
                $allSubGenre = array_merge($allSubGenre, json_decode($output));
            }
        }
        Utility::debug(array_unique($allSubGenre), true);
        $data = self::adminData();
    }
}
