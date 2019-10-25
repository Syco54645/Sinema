<?php
class Utility {

    public static function lastQuery($die=true, $backtrace=true) {
        if($backtrace){
            $backtrace = debug_backtrace();
            echo "<b>".$backtrace[0]['file'] . ' on ' . $backtrace[0]['line']."</b><br/><br/>";
        }

        $CI =& get_instance();
        echo $CI->db->last_query();
        if ($die) {
            die();
        }
    }

    public static function debug($asd, $die=true, $backtrace=true) {

        if($backtrace){
            $backtrace = debug_backtrace();
            echo "<b>".$backtrace[0]['file'] . ' on ' . $backtrace[0]['line']."</b>";
        }
        echo "<pre>";
        var_dump($asd);
        echo "</pre>";
        if ($die) {
            die();
        }
    }

    public static function getPlexUrl($type='library') {

        $CI =& get_instance();
        $CI->load->model('SettingsModel');

        $plexUrl = $CI->SettingsModel->getSettingValueByName("Plex Url");

        $url = $plexUrl . "/library/sections/%d/all?X-Plex-Token=%s";
        switch ($type) {
            case 'all-libraries':
                $url = $plexUrl . "/library/sections/?X-Plex-Token=%s";
                break;
            case 'library':
                $url = $plexUrl . "/library/sections/%d/all?X-Plex-Token=%s";
                break;
            case 'metadata':
                $url = $plexUrl . "%s/all?X-Plex-Token=%s";
                break;
            case 'transcode':
                $url = $plexUrl . "/photo/:/transcode/?%s&url=%s&X-Plex-Token=%s";
                //p://url:32400/photo/:/transcode?width=<width px>&height=<height px>&url=<url of the photo safe encoded>
                break;
            default:
                $url = $plexUrl . "/library/sections/%d/all?X-Plex-Token=%s";
                break;
        }
        return $url;
    }

    public static function convertXmlToJson($data) {

        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        //Utility::debug($array, true);
        return $json;
    }

    public static function getPlexThumbnailUrl($thumbnail, $plexApiToken, $dimensions="width=300&height=450") {

        if ($dimensions != null) {
            $thumbnailUrl = sprintf(Utility::getPlexUrl("transcode"), $dimensions, $thumbnail, $plexApiToken);
        } else {
            $thumbnailUrl = sprintf(Utility::getPlexUrl("metadata"), $thumbnail, $plexApiToken);
        }
        return $thumbnailUrl;
    }

    public static function getImdbId($guid) {

        $imdbId = null;
        $regex = "/tt[0-9]*/";
        $matches = [];
        if (preg_match($regex, $guid, $matches)) {
            $imdbId = $matches[0];
        }
        return $imdbId;
    }

    public static function slugify($name) {

        return strtolower(str_replace(" ", "-", $name));
    }

    public static function splitSemiColon($string) {

        $splitted = explode(";", trim($string));
        $splitted = array_map('trim', $splitted);
        return array_filter($splitted);
    }

    public static function getWantedTags($movieTags=[]) {

        $CI =& get_instance();
        $CI->load->model('SettingsModel');

        $keptTags = Utility::splitSemiColon($CI->SettingsModel->getSettingValueByName("Kept Tags"));
        $keptTags = array_map('Utility::slugify', $keptTags);

        $newTags = [];

        // FIXME this is a hack, this needs removed
        if (!is_array($movieTags)) {
            $movieTags = [];
        }
        foreach ($movieTags as $tag) {
            if (in_array(Utility::slugify($tag), $keptTags)) {
                $newTags[] = Utility::slugify($tag);
            }
        }
        return $newTags;
    }

    public static function getMovieTags($imdbId) {

        $command = escapeshellcmd('python ./python/keywords.py ' . str_replace('tt', '', $imdbId));
        $output = shell_exec($command);
        $movieTags = Utility::getWantedTags(json_decode($output));
        return $movieTags;
    }

    public static function getSettings() {
        $CI =& get_instance();
        $CI->load->model('SettingsModel');

        $settings = $CI->SettingsModel->getSettings();

        $saneSettings = [];
        foreach ($settings as $setting) {
            $saneSettings[$setting['setting_slug']] = $setting['setting_value'];
        }

        return $saneSettings;

    }

    public static function checkSettingEnabled($slug) {
        $CI =& get_instance();
        return $CI->config->sinemaSettings[$slug] == "1";
    }

    public static function getPost() {
        // really starting to doubt my decision of using CI at this point...
        // fix found here https://www.itsolutionstuff.com/post/codeigniter-angularjs-http-post-not-workingexample.html
        return json_decode(file_get_contents('php://input'), true);
    }
}
