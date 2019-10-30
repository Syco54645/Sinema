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

    public function export_plex() {
        $data = $this->starterData;
        $data['title'] = "Export Collection From Plex";

        $libraries = $this->_getPlexLibraries();
        $data['formattedLibraries'] = $libraries['formattedLibraries'];
        $data['libraries'] = $libraries['libraries'];

        $this->load->view('partials/template-header', $data);
        $this->load->view('admin/v_export_plex', $data);
        $this->load->view('partials/template-footer', $data);
    }

    private function _getPlexLibraries() {

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
        $libraries = [];
        $libraries['formattedLibraries'] = $formattedLibraries;

        $libraries['libraries'] = $this->filmmodel->getLibraryAliases();
        return $libraries;
    }

    public function import_plex() {
        $data = $this->starterData;
        $data['title'] = "Import Collection From Plex";

        $libraries = $this->_getPlexLibraries();
        $data['formattedLibraries'] = $libraries['formattedLibraries'];
        $data['libraries'] = $libraries['libraries'];

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
        $movie['tagline'] = isset($video['tagline']) ? $video['tagline'] : null;

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

        $movie['director'] = null;
        if (isset($video['Director'])) {
            $movie['director'] = [];
            foreach ($video['Director'] as $director) {
                $movie['director'][] = $director['tag'];
            }
        }

        $movie['writer'] = null;
        if (isset($video['Writer'])) {
            $movie['writer'] = [];
            foreach ($video['Writer'] as $writer) {
                $movie['writer'][] = $writer['tag'];
            }
        }

        $movie['role'] = null;
        if (isset($video['Role'])) {
            $movie['role'] = [];
            foreach ($video['Role'] as $role) {
                $movie['role'][] = $role['tag'];
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

    public function ajaxExportPlex() {

        $this->load->model('SettingsModel');

        $plexApiKey = $this->config->sinemaSettings['plex-api-token'];
        $_POST = Utility::getPost();
        $libraryType = $this->input->post('libraryType');
        $libraryId = $this->input->post('libraryId');

        $service_url = sprintf(Utility::getPlexUrl("library"), $libraryId, $plexApiKey);

        $get_data = $this->filmmodel->callAPI('GET', $service_url, false);
        $get_data_array = json_decode($get_data, TRUE);

        $maxSubjects = [
            'country' => 0,
            'genre' => 0,
            'director' => 0,
            'writer' => 0,
            'role' => 0,
        ];

        $movies = [];
        foreach($get_data_array['MediaContainer']["Metadata"] as $k=>$video) {
            $movie = $this->_get_movie_data_from_plex_object($video);
            $movie = $this->_get_export_data_from_plex_object($video, $movie);

            $this->_getMaxSubjects($maxSubjects, $movie);

            $movies[] = $movie;
        }
//Utility::debug($maxSubjects, true);
        $csvResult = $this->_create_csv($movies, $maxSubjects);

        $csv = $csvResult['csv'];
        $duplicates = $csvResult['duplicates'];


        // todo add something so that we know if it fails

        $response = array(
            'csv' => $csv,
            'duplicates' => $duplicates,
        );
        $jsonResponse = new JsonResponse();

        echo $jsonResponse->create('ok', '', $response);
    }

    private function _getMaxSubjects(&$maxSubjects, $movie) {

        if (count($movie['country']) > $maxSubjects['country']) {
            $maxSubjects['country'] = count($movie['country']);
        }
        if (count($movie['genre']) > $maxSubjects['genre']) {
            $maxSubjects['genre'] = count($movie['genre']);
        }
        if (count($movie['director']) > $maxSubjects['director']) {
            $maxSubjects['director'] = count($movie['director']);
        }
        if (count($movie['writer']) > $maxSubjects['writer']) {
            $maxSubjects['writer'] = count($movie['writer']);
        }
        if (count($movie['role']) > $maxSubjects['role']) {
            $maxSubjects['role'] = count($movie['role']);
        }

        return $maxSubjects;
    }

    private function _generateCsvHeaderLine($maxSubjects, $containsFile = true) {

        $headerLine = "identifier,file,mediatype,description,year,title,collection";
        if (!$containsFile) {
            $headerLine = "identifier,description,year,title,collection";
        }

        foreach ($maxSubjects as $subject => $count) {

            switch ($subject) {
                case 'genre':
                    $label = "subject[%s]";
                    break;

                case 'role':
                    $label = "actor[%s]";
                    break;

                default:
                    $label = $subject . "[%s]";
                    break;
            }

            for ($i = 0; $i < $count; $i++) {
                $headerLine .= ',' . sprintf($label, $i);
            }
        }

        return $headerLine;
    }

    private function _addRepeaterToLine(&$line, $movie, $maxSubjects) {

        foreach ($maxSubjects as $subject => $count) {
            if (is_array($movie[$subject])) {
                foreach ($movie[$subject] as $label) {
                    $line[] = $label;
                }
            }

            $count = count($movie[$subject]);
            while ($count < $maxSubjects[$subject]) {
                $line [] = "";
                $count++;
            }
        }
    }

    private function _create_csv($movies, $maxSubjects) {

        $collectionName = $this->input->post('collectionName');
        $exportType = $this->input->post('exportType');
        $identifierPrefix = $this->input->post('identifierPrefix');

        $containsFile = true;
        if ($exportType == 'metadata') {
            $containsFile = false;
        }

        $headerLine = $this->_generateCsvHeaderLine($maxSubjects, $containsFile);
        $csv = [
            $headerLine,
        ];

        $duplicates = [];

        foreach ($movies as $movie) {
            /*
            forgot that my slugify function wasnt fully done and a few uploads failed because of characters in the identifier
            shouldnt be needed again but leaving logic here so that it can be reused if need be
            $woopsed = [
                '/[^A-Za-z0-9 ]/'
            ];
            $badChar = false;
            foreach ($woopsed as $woops) {
                if (preg_match($woops, $movie['title'])) {
                    $badChar = true;
                }
            }
            if (!$badChar) continue;
            if (!empty($movie['summary'])) continue;
            */
            $line = [];
            // todo make the identiferPrefix a variable
            $identiferPrefix = 'sinema-trailer_';
            $idenfifierResults = $this->_createIdentifier($csv, $movie, $identiferPrefix);
            $identifier = $idenfifierResults['identifier'];
            if ($idenfifierResults['duplicate'] == true) {
                $duplicates[] = $movie;
            }

            /*
            $missing = [
                'sinema-trailer_savage-weekend',
            ];
            if (in_array($identifier, $missing)) continue;
            */

            //Utility::debug($identifier, true);
            $line[] = $identifier;
            if ($containsFile) {
                $line[] = $movie['file'];
                $line[] = 'movie';
            }
            $line[] = $movie['summary'];
            $line[] = $movie['year'];
            $line[] = $movie['title'] . ' [' . $movie['year'] . '] - Trailer';
            $line[] = $collectionName;

            $this->_addRepeaterToLine($line, $movie, $maxSubjects);

            $stringLine = $this->_arrayToCsvLine($line);
            $csv[] = $stringLine;
        }
        $csv = implode("\r\n", $csv);

        return ['csv' => $csv, 'duplicates' => $duplicates ];

    }

    private function _createIdentifier($csv, $movie, $identiferPrefix) {

        $identifier = $identiferPrefix . Utility::Slugify($movie['title']);
        $identifier = substr($identifier ,0, 50);

        $duplicate = false;
        $hasYear = false;
        $ct = 0;
        while ($this->_substr_in_array($identifier, $csv)) {
            $length = 50 - (strlen($identiferPrefix) + strlen($movie['year']) + 1);
            $shortenedTitle = substr($movie['title'], 0, $length);
            $identifier = $identiferPrefix . Utility::Slugify($shortenedTitle . ' ' . $movie['year']);
            if ($hasYear == true) {
                $length = 50 - (strlen($identiferPrefix) + strlen($movie['year']) + 3);
                $shortenedTitle = substr($movie['title'], 0, $length);
                $identifier = $identiferPrefix . Utility::Slugify($shortenedTitle . ' ' . $movie['year'] . ' ' . $ct);
            }
            $hasYear = true;
            $duplicate = true;
            $ct++;
        }

        return ['identifier' => $identifier, 'duplicate' => $duplicate];
    }

    private function _substr_in_array($needle, array $haystack) {

        $filtered = array_filter($haystack, function ($item) use ($needle) {
            return false !== strpos($item, $needle);
        });

        return !empty($filtered);
    }

    private function _arrayToCsvLine($values) {
        // taken from https://stackoverflow.com/questions/6325613/escaping-for-csv
        $line = '';
        $values = array_map(function ($v) {
            return '"' . str_replace('"', '""', $v) . '"';
        }, $values);

        $line .= implode(',', $values);

        return $line;
    }

    private function _get_export_data_from_plex_object($video, $movie) {
        $movie['file'] = $video['Media'][0]['Part'][0]['file'];
        return $movie;
    }
}
