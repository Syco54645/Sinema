<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grindhouse extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('AuthModel', 'auth');
        if ($this->starterData['currentRoute'] != 'grindhouse-index') {
            if (!$this->auth->isAuthed()) {
                redirect('/admin/login');
            }
        }
        $this->load->model('FilmModel', 'filmmodel');
        $this->load->model('PrerollModel', 'prerollmodel');
        $this->load->model('TrailerModel', 'trailermodel');
        $this->load->model('GrindhouseModel', 'grindhousemodel');
    }

    public function index() {

        $data = $this->starterData;

        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_index');
        $this->load->view('partials/template-footer', $data);
    }

    public function manage() {
        $data = $this->starterData;

        $options = [];

        $data['grindhouses'] = $this->grindhousemodel->getGrindhouses($options);

        $data['title'] = "Grindhouses (" . count($data['grindhouses']) . ")";

        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_manage');
        $this->load->view('partials/template-footer', $data);
    }

    public function edit($grindhouseId) {
        $data = $this->starterData;

        $data['grindhouse'] = $this->grindhousemodel->getGrindhouseById($grindhouseId);
        $data['grindhouseId'] = $grindhouseId;

        $data['assembledFeature'] = $this->_assembleFeatureFromCommand($data['grindhouse']);
        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_edit');
        $this->load->view('partials/template-footer', $data);
    }

    private function _assembleFeatureFromCommand($grindhouse) {
        $elementsJson = json_decode($grindhouse['command']);
        $elements = [];

        foreach ($elementsJson as $element) {
            $temp = [];
            $temp['type'] = $element->type;
            switch ($element->type) {
                case 'Intro Preroll':
                case 'Feature Presentation Preroll':
                case 'Joiner Preroll':
                case 'Outro Preroll':
                case 'Intermission Preroll':
                    $temp['item'] = $this->prerollmodel->getPrerollById($element->data->id);
                    break;

                case 'Trailer':
                    foreach ($element->data as $e) {
                        $temp['item'][] = $this->trailermodel->getTrailerById($e->id);
                    }
                    $elements[] = $temp;
                    break;

                case 'Film':
                    $temp['item'] = $this->filmmodel->getFilmById($element->data->id);
                    break;

                default:
                    $temp['item'] = $this->trailermodel->getTrailerById($element->data->id);
                    break;
            }
            $elements[] = $temp;
        }

        return $elements;
    }

    public function create() {

        $data = $this->starterData;

        $data['genres'] = $this->filmmodel->getGenres();
        $data['tags'] = $this->filmmodel->getTags();

        if (Utility::checkSettingEnabled('enable-prerolls')) {
            $data['prerollSeries'] = $this->prerollmodel->getPrerollSeries();
        }

        $this->load->view('partials/template-header', $data);
        $this->load->view('grindhouse/v_create');
        $this->load->view('partials/template-footer', $data);

    }

    public function ajaxSave() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();

            $qd = [
                'title' => $this->input->post('title'),
                'tagline' => $this->input->post('tagline'),
                'show_date' => $this->input->post('showDate'),
            ];
            $grindhouseId = $this->input->post('id');

            $this->grindhousemodel->updateGrindhouse($grindhouseId, $qd);

            $response = [];
            $jsonResponse = new JsonResponse();

            echo $jsonResponse->create('ok', '', $response);
        }
    }

    public function ajaxCreate() {
        $films = [];

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $filmIds = $this->filmmodel->featureSearch($this->input->post('search'));

            $selectedFilms = $this->_getFilms($filmIds);

            $film1 = $selectedFilms[0];
            $film2 = $selectedFilms[1];

            $sortedPrerolls = $this->_getPrerolls();

            $format = "[introPreroll]
[trailer*]
[joinerPreroll]
[trailer*]
[featurePresentationPreroll]
[film]
[intermissionPreroll]
[trailer*]
[joinerPreroll]
[trailer*]
[film]
[outroPreroll]";

            $trailers = [
                'num' => $_POST['search']['criteria']['trailers']['number'],
                'items' => $this->trailermodel->getRandomTrailers($_POST['search']['criteria']['trailers']['number'])
            ];
            $assembledFeature = $this->_assembleFeature($format, $sortedPrerolls, $selectedFilms, $trailers);
            //$this->_processAssembledFeatureText($assembledFeature);
            $command = $this->_processAssembledFeatureJson($assembledFeature);

            $qd = [
                'title' => 'Sinema - ' . date('Y-m-d H:i:s'),
                'tagline' => '',
                'command' => $command,
                'command_version' => '.1',
            ];

            $grindhouseId = $this->grindhousemodel->storeGrindhouse($qd);

            $response = array(
                'command' => $command,
                'assembledFeature' => json_encode($assembledFeature),
                'id' => $grindhouseId,
            );
            $jsonResponse = new JsonResponse();

            echo $jsonResponse->create('ok', '', $response);
        }
    }

    public function ajaxCreatePlexPlaylist() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $_POST = Utility::getPost();
            $id = $this->input->post('id');

            echo Utility::createPlexPlaylist($id);
        }
    }

    private function _processAssembledFeatureText($assembledFeature) {

        $return = "";
        foreach($assembledFeature as $featureItem) {

            if ($featureItem['type'] == 'Trailer') {
                $return .= "<b>" . $featureItem['type'] . "</b>" . ': ';
                foreach($featureItem['item'] as $item) {
                    $return .= $item['id'] . ' ' . $item['title'] . "<br>";
                }
            } else {
                $return .= "<b>" . $featureItem['type'] . "</b>" . ': ' . $featureItem['item']['id'] . ' ' . $featureItem['item']['title'] . "<br>";
            }
        }

        return $return;
    }

    private function _processAssembledFeatureJson($assembledFeature) {

        $return = [];
        foreach($assembledFeature as $featureItem) {
            if ($featureItem['type'] == 'Trailer') {
                $temp = [
                    'type' => $featureItem['type'],
                    'data' => [],
                ];
                foreach($featureItem['item'] as $item) {
                    $temp1 = [
                        'libraryId' => $item['library_id'],
                        'title' => $item['title'],
                        'id' => $item['id'],
                    ];
                    $temp['data'][] = $temp1;
                }
            } else {
                $temp = [
                    'type' => $featureItem['type'],
                    'data' => [
                        'libraryId' => $featureItem['item']['library_id'],
                        'title' => $featureItem['item']['title'],
                        'id' => $featureItem['item']['id'],
                    ],
                ];
            }
            $return[] = $temp;
        }

        return json_encode($return);
    }

    private function _assembleFeature($format, $sortedPrerolls, $selectedFilms, $trailers) {
        $formatArray = explode("\n", $format);
        $trailerBreaks = array_count_values($formatArray)['[trailer*]'];

        usort($selectedFilms, function ($item1, $item2) {
            if ($item1['rating'] == $item2['rating']) return 0;
            return $item1['rating'] < $item2['rating'] ? -1 : 1;
        });
        $complete = [];
        $filmIndex = 0;
        $trailerIndexes = [];
        foreach ($formatArray as $i=>$item) {
            $temp = [];
            switch ($item) {
                case '[introPreroll]':
                    $temp['type'] = 'Intro Preroll';
                    $prerollIndex = rand(0, count($sortedPrerolls['introPrerolls']['seriesIndexes']) -1 );
                    $preroll = $sortedPrerolls['introPrerolls']['prerollObjects'][$prerollIndex];
                    $temp['item'] = $preroll;
                    break;
                case '[featurePresentationPreroll]':
                    $temp['type'] = 'Feature Presentation Preroll';
                    $prerollIndex = rand(0, count($sortedPrerolls['featurePresentationPrerolls']['seriesIndexes']) -1 );
                    $preroll = $sortedPrerolls['featurePresentationPrerolls']['prerollObjects'][$prerollIndex];
                    $temp['item'] = $preroll;
                    break;
                case '[film]':
                    $temp['type'] = 'Film';
                    $temp['item'] = $selectedFilms[$filmIndex];
                    $filmIndex++;
                    break;
                case '[intermissionPreroll]':
                    $temp['type'] = 'Intermission Preroll';
                    $prerollIndex = rand(0, count($sortedPrerolls['intermissionPrerolls']['seriesIndexes']) -1 );
                    $preroll = $sortedPrerolls['intermissionPrerolls']['prerollObjects'][$prerollIndex];
                    $temp['item'] = $preroll;
                    break;
                case '[joinerPreroll]':
                    $temp['type'] = 'Joiner Preroll';
                    $prerollIndex = rand(0, count($sortedPrerolls['joinerPrerolls']['seriesIndexes']) -1 );
                    $preroll = $sortedPrerolls['joinerPrerolls']['prerollObjects'][$prerollIndex];
                    $temp['item'] = $preroll;
                    break;
                case '[trailer*]':
                    $temp['type'] = 'Trailer';
                    $temp['item'] = [
                        array_pop($trailers['items']),
                    ];
                    $trailerIndexes[] = $i;
                    break;
                case '[outroPreroll]':
                    $temp['type'] = 'Outro Preroll';
                    $prerollIndex = rand(0, count($sortedPrerolls['outroPrerolls']['seriesIndexes']) -1 );
                    $preroll = $sortedPrerolls['outroPrerolls']['prerollObjects'][$prerollIndex];
                    $temp['item'] = $preroll;
                    break;
            }
            $complete[] = $temp;
        }

        for ($i = count($trailers['items']); $i > 0; $i--) {
            $trailerStoreIndex = $trailerIndexes[rand(0, count($trailerIndexes) -1 )];
            $complete[$trailerStoreIndex]['item'][] = array_pop($trailers['items']);
        }

        return $complete;
    }

    private function _storeSortedPreroll($sortedPrerolls, $preroll, $wantedPrerollSeries) {
        $next = sizeof($sortedPrerolls['prerollObjects']);
        $sortedPrerolls['prerollObjects'][] = $preroll;
        if ($wantedPrerollSeries != null && $preroll['preroll_series_id'] == $wantedPrerollSeries) {
            $sortedPrerolls['seriesIndexes'][] = $next;
        } elseif ($wantedPrerollSeries == null) {
            $sortedPrerolls['seriesIndexes'][] = count($sortedPrerolls['prerollObjects']) - 1;
        }
        return $sortedPrerolls;
    }

    // numberOfFilmsWanted is not implemented but here for future proofing
    // the function to create the grindhouse feature will need completely rewritten to handle more than 2 anyway
    // mostly did it now for a simple check so that we did not end up in an endless loop situation
    private function _getFilms($filmIds, $numberOfFilmsWanted = 2) {

        if ($numberOfFilmsWanted > count($filmIds)) {
            die('Wanted ' . $numberOfFilmsWanted . ' but only ' . count($filmIds) . ' match the current criteria');
        }

        $returnData = [];
        $currentFilmId = $filmIds[rand(0, count($filmIds) -1 )];
        $selectedFilmIds = [
            $currentFilmId,
        ];
        $returnData[] = $this->filmmodel->getFilmById($currentFilmId);

        for ($i = 0; $i < $numberOfFilmsWanted - 1; $i++) {
            // get random position in the array
            $currentFilmId = $filmIds[rand(0, count($filmIds) -1 )];
            do {
                $currentFilmId = $filmIds[rand(0, count($filmIds) -1 )];
            } while (in_array($currentFilmId, $selectedFilmIds));

            $returnData[] = $this->filmmodel->getFilmById($currentFilmId);
        }

        return $returnData;
    }

    private function _getPrerolls() {
        if (Utility::checkSettingEnabled('enable-prerolls') && $_POST['search']['selected']['prerolls']) {
//Utility::debug($_POST['search']['criteria']['prerolls'], true);
            $wantedPrerollSeries = null;
            if ($_POST['search']['criteria']['prerolls']['stayInSeries'] == true) {
                $wantedPrerollSeries = $_POST['search']['criteria']['prerolls']['selectedSeries'];
            }

            /*
            bettrer way to do prerolls
            pull all in
            do a search on the subkey to find the preroll series
            if you dont find any it will just pull a random from the array
             */

            $prerolls = $this->prerollmodel->getPrerolls(/*$options*/);

            $sortedPrerolls = [
                'introPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
                'intermissionPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
                'joinerPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
                'featurePresentationPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
                'informationPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
                'outroPrerolls' => [
                    'seriesIndexes' => [],
                    'prerollObjects' => [],
                    'seriesNotFoundError' => false,
                ],
            ];

            foreach ($prerolls as $preroll) {
                switch ($preroll['preroll_type_slug']) {

                    case 'intro':
                        $sortedPrerolls['introPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['introPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    case 'intermission':
                        $sortedPrerolls['intermissionPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['intermissionPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    case 'joiner':
                        $sortedPrerolls['joinerPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['joinerPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    case 'feature-presentation':
                        $sortedPrerolls['featurePresentationPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['featurePresentationPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    case 'information':
                        $sortedPrerolls['informationPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['informationPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    case 'outro':
                        $sortedPrerolls['outroPrerolls'] = $this->_storeSortedPreroll($sortedPrerolls['outroPrerolls'], $preroll, $wantedPrerollSeries);
                        break;

                    default:
                        # code...
                        break;
                }
            }

            if ($wantedPrerollSeries != null) {
                foreach ($sortedPrerolls as &$sortedPreroll) {
                    if (count($sortedPreroll["seriesIndexes"]) == 0) {
                        $sortedPreroll['seriesNotFoundError'] = true;
                        // preseed the seriesIndexes since we have the error set above
                        // while this may be confusing given the name it will be easiest to work with this way
                        $sortedPreroll["seriesIndexes"] = range(0, count($sortedPreroll["prerollObjects"]) - 1);
                    }
                }
            }
//                Utility::debug($sortedPrerolls, true);
        }
        return $sortedPrerolls;
    }

    public function upcoming() {

    }

    public function past() {

    }

    public function calendar() {

    }

}
