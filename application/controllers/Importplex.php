<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportPlex extends MY_Controller {

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
        $this->load->model('PrerollModel', 'prerollmodel');
        $this->load->model('TrailerModel', 'trailermodel');

        $this->plexApiToken = $this->config->sinemaSettings['plex-api-token'];
    }

    public function import_plex() {
        $data = $this->starterData;
        $data['title'] = "Import Collection From Plex";

        $this->load->model('SettingsModel');
        $plexApiKey = $this->config->sinemaSettings['plex-api-token'];

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

        $data['libraries'] = $this->filmmodel->getLibraryAliases();

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_import_plex', $data);
        $this->load->view('partials/template-footer', $data);
    }

    private function _get_movie_data_from_plex_object($video) {

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

        return $movie;
    }

    private function _plex_movie_step_one($service_url, $libraryId) {
        /*
        ** insert movies
        ** insert genres
        ** link movies to genres
        */
        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $get_data_array = json_decode($get_data, TRUE);

        foreach($get_data_array['MediaContainer']["Metadata"] as $k=>$video) {
            $movie = $this->_get_movie_data_from_plex_object($video);
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
                'library_id' => $libraryId,
            ];

            // insert the film if it doesnt exist
            if ($this->filmmodel->checkFilmExists($movie['id']) === 0) {
                $this->filmmodel->storeFilm($qd);
            } else {
                unset($qd['id']);
                $this->filmmodel->updateFilm($movie['id'], $qd);
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

            $movieTags = Utility::getMovieTags($movie['imdbId']);
            //Utility::debug($movie['imdbId'], false);

            foreach ($movieTags as $tag) {
                $qd = [
                    "tag" => $tag,
                    "tag_slug" => Utility::slugify($tag),
                ];
                $tagId = isset($this->filmmodel->getTagBySlug($qd['tag_slug'])->id) ? $this->filmmodel->getTagBySlug($qd['tag_slug'])->id : null;

                if (!$tagId) {
                    $tagId = $this->filmmodel->storeTag($qd);
                }

                $this->filmmodel->mapTagToFilm($movie['id'], $tagId);
            }
        }
        $more = true;
        if (count($movies) < $this->input->post('numFilms')) {
            $more = false;
        }
        // todo add something so that we know if it fails
        return ['status' => 'success', 'more' => $more ];
    }

    private function _plex_preroll_step_one($service_url, $libraryId) {
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
                'library_id' => $libraryId,
            ];

            // insert the film if it doesnt exist
            if ($this->prerollmodel->checkPrerollExists($preroll['id']) === 0) {
                $this->prerollmodel->storePreroll($qd);
            } else {
                unset($qd['id']);
                $this->prerollmodel->updatePreroll($preroll['id'], $qd);
            }
        }
        // todo add something so that we know if it fails
        return ['status' => 'success'];
    }

    private function _plex_trailer_step_one($service_url, $libraryId) {

        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $get_data_array = json_decode($get_data, TRUE);

        foreach($get_data_array['MediaContainer']["Metadata"] as $k=>$video) {
            $movie = $this->_get_movie_data_from_plex_object($video);
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
                'library_id' => $libraryId,
            ];

            // insert the trailer if it doesnt exist
            if ($this->trailermodel->checkTrailerExists($movie['id']) === 0) {
                $this->trailermodel->storeTrailer($qd);
            } else {
                unset($qd['id']);
                $this->trailermodel->updateTrailer($movie['id'], $qd);
            }
        }
        // todo add something so that we know if it fails
        return ['status' => 'success'];
    }

    public function ajaxImportPlex($step) {

        $this->load->model('SettingsModel');

        $plexApiKey = $this->config->sinemaSettings['plex-api-token'];
        $_POST = Utility::getPost();
        $type = $this->input->post('type');
        $libraryId = $this->input->post('libraryId');
        $libraryAlias = $this->input->post('libraryAlias') ? $this->input->post('libraryAlias') : null;

        if ($libraryAlias != null) {
            $qd = [
                'id' => $libraryId,
                'library_name' => $libraryAlias,
                'library_name_slug' => Utility::slugify($libraryAlias),
                'library_type' => $type,
            ];
            $this->filmmodel->insertLibraryAlias($qd);
        }
        $service_url = sprintf(Utility::getPlexUrl("library"), $libraryId, $plexApiKey);
        switch ($type) {
            case 'preroll':
                if ($step == 1) {
                    $response = $this->_plex_preroll_step_one($service_url, $libraryId);
                }
                break;
            case 'movie':
                if ($step == 1) {
                    $response = $this->_plex_movie_step_one($service_url, $libraryId);
                } elseif ($step == 2) {
                    $response = $this->_plex_movie_step_two($service_url);
                }
                break;
            case 'trailer':
                if ($step == 1) {
                    $response = $this->_plex_trailer_step_one($service_url, $libraryId);
                }
            default:
                # code...
                break;
        }

        echo json_encode($response);
    }
}
