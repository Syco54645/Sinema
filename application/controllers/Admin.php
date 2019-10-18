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

        $this->load->model('SettingsModel', 'settingsmodel');
        foreach($_POST as $setting => $settingValue) {
            $this->settingsmodel->saveSetting($setting, $settingValue);

        }

        $data['title'] = "Settings";

        $settings = $this->settingsmodel->getSettings();
        $data['settings'] = $settings;

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_settings', $data);
        $this->load->view('partials/template-footer', $data);
    }

    public function import_plex() {
        $data = self::adminData();
        $data['title'] = "Import Collection From Plex";
        
        $this->load->model('SettingsModel');
        $plexApiKey = $this->SettingsModel->getSettingValueByName("Plex API Token");

        $service_url = sprintf(Utility::getPlexUrl("all-libraries"), $plexApiKey);

        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $libraries = json_decode($get_data, TRUE);
        $formattedLibraries = [];
        foreach ($libraries["MediaContainer"]["Directory"] as $library) {
            $temp = [
                'title' => $library["title"],
                'id' => $library["key"],
            ];
            $formattedLibraries[] = $temp;
        }
        $data['formattedLibraries'] = $formattedLibraries;

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_import_plex', $data);
        $this->load->view('partials/template-footer', $data);
    }

    private function _plex_movie_step_one($service_url) {
        /* 
        ** insert movies
        ** insert genres
        ** link movies to genres
        */
        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $get_data_array = json_decode($get_data, TRUE);

        foreach($get_data_array['MediaContainer']["Metadata"] as $k=>$video) {
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

            $movie['thumbUrl'] = Utility::getPlexThumbnailUrl($movie['thumb'], $this->plexApiToken);
            $movie['artUrl'] = Utility::getPlexThumbnailUrl($movie['art'], $this->plexApiToken);
            /*echo '<img src="' . $movie['thumbUrl'] . '" />';
            echo '<img src="' . $movie['artUrl'] . '" />';*/
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

            // insert the film if it doesnt exist
            if ($this->filmmodel->checkFilmExists($movie['id']) === 0) {
                $this->filmmodel->storeFilm($qd);
            }

            if ($movie['genre'] != null) {
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
            }
        }
        // todo add something so that we know if it fails
        return ['status' => 'success'];
    }

    private function _plex_movie_step_two($service_url) {

        $_POST = Utility::getPost();
//Utility::debug($this->input->post('numFilms'), false);
//Utility::debug($this->input->post('offset'), false);
        $options = [
            "limit" => $this->input->post('numFilms'),
            "offset" => $this->input->post('offset'),
        ];

        $movies = $this->filmmodel->getFilms($options);
        foreach ($movies as $k=>$movie) {
            
            $movieSubgenres = Utility::getMovieSubgenres($movie['imdbId']);
//Utility::debug($movie['imdbId'], false);

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
        }
        $more = true;
        if (count($movies) < $this->input->post('numFilms')) {
            $more = false;
        }
        // todo add something so that we know if it fails
        return ['status' => 'success', 'more' => $more ];
    }

    private function _plex_preroll_step_one($service_url) {
        /* 
        ** insert movies
        ** insert genres
        ** link movies to genres
        */
        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $get_data_array = json_decode($get_data, TRUE);

        foreach($get_data_array['MediaContainer']["Metadata"] as $k=>$video) {
            $preroll = [];
            $preroll['id'] = isset($video['ratingKey']) ? $video['ratingKey'] : null;
            $preroll['title'] = isset($video['title']) ? $video['title'] : null;
            $preroll['summary'] = isset($video['summary']) ? $video['summary'] : null;
            $preroll['thumb'] = isset($video['thumb']) ? $video['thumb'] : null;
            $preroll['art'] = isset($video['art']) ? $video['art'] : null;
            $preroll['guid'] = isset($video['guid']) ? $video['guid'] : null;
            
            $preroll['thumbUrl'] = Utility::getPlexThumbnailUrl($preroll['thumb'], $this->plexApiToken);
            $preroll['artUrl'] = Utility::getPlexThumbnailUrl($preroll['art'], $this->plexApiToken);
            /*echo '<img src="' . $preroll['thumbUrl'] . '" />';
            echo '<img src="' . $preroll['artUrl'] . '" />';*/
            $qd = [
                'id' => $preroll['id'],
                'title' => $preroll['title'],
                'summary' => $preroll['summary'],
                'thumb' => $preroll['thumb'],
                'art' => $preroll['art'],
                'guid' => $preroll['guid'],
                'thumbUrl' => $preroll['thumbUrl'],
                'artUrl' => $preroll['artUrl'],
            ];

            // insert the film if it doesnt exist
            if ($this->filmmodel->checkPrerollExists($preroll['id']) === 0) {
                $this->filmmodel->storePreroll($qd);
            }
        }
        // todo add something so that we know if it fails
        return ['status' => 'success'];
    }

    public function ajaxImportPlex($step) {

        $this->load->model('SettingsModel');

        $plexApiKey = $this->SettingsModel->getSettingValueByName("Plex API Token");
        $_POST = Utility::getPost();
        $type = $this->input->post('type');
        $libraryId = $this->input->post('libraryId');

        switch ($type) {
            case 'preroll':
                $service_url = sprintf(Utility::getPlexUrl("library"), $libraryId, $plexApiKey);
                if ($step == 1) {
                    $response = $this->_plex_preroll_step_one($service_url);
                }
                break;
            case 'movie':
                $service_url = sprintf(Utility::getPlexUrl("library"), $libraryId, $plexApiKey);
                if ($step == 1) {
                    $response = $this->_plex_movie_step_one($service_url);
                } elseif ($step == 2) {
                    $response = $this->_plex_movie_step_two($service_url);
                }
                break;
            default:
                # code...
                break;
        }

        echo json_encode($response);
    }

    public function do_import_plex($step=1) {

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
    }

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
