<?php
class MY_Model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function callAPI ($method, $url, $data) {
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
            break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                              
                }
             break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);

       return $result;
    }

    public function insert_ignore($table, array $data) {

        $_prepared = array();

        foreach ($data as $col => $val) {
            $_prepared[$this->db->escape($col)] = $this->db->escape($val);
        }

        $this->db->query('INSERT OR IGNORE INTO `'.$table.'` ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }
}