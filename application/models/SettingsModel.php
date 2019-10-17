<?php 
class SettingsModel extends MY_Model {

    public function getSettings() {

        $this->db->select('*');
        $this->db->from('settings');
        $this->db->order_by('sort_order', 'asc');

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getSettingByName($name) {
        
        $this->db->select('*');
        $this->db->from('settings');
        $this->db->where('setting_name', $name);

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getSettingBySlug($slug) {
        
        $this->db->select('*');
        $this->db->from('settings');
        $this->db->where('setting_slug', $slug);

        $result = $this->db->get()->row()->setting_value;
        return $result;
    }

    public function getSettingValueByName($name) {
        
        $this->db->select('setting_value');
        $this->db->from('settings');
        $this->db->where('setting_name', $name);
        
        $result = $this->db->get()->row()->setting_value;

        return $result;
    }

    public function saveSetting($slug, $value) {

        $this->db->set('setting_value', $value);
        $this->db->where('setting_slug', $slug);
        $this->db->update('settings');
    }
}