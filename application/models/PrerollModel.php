<?php
class PrerollModel extends MY_Model {

    public function checkPrerollExists($id) {

        $this->db->select('*');
        $this->db->from('prerolls');
        $this->db->where('id', $id);

        return $this->db->count_all_results();
    }

    public function storePreroll($qd) {

        $this->db->insert('prerolls', $qd);
        return $this->db->insert_id();
    }

    public function getPrerolls($options = []) {

        $this->db->select('p.*, pt.preroll_type_name, pt.preroll_type_slug, pt.preroll_type_description, ps.preroll_series_name');
        $this->db->from('prerolls p');
        $this->db->join('preroll_type pt', 'p.preroll_type_id=pt.id', 'left outer');
        $this->db->join('preroll_series ps', 'preroll_series_id=ps.id', 'left outer');
        if (isset($options['seriesId'])) {
            $this->db->where('preroll_series_id', $options['seriesId']);
        }
        if (isset($options['typeId'])) {
            $this->db->where('preroll_type_id', $options['typeId']);
        }

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getPrerollById($id) {

        $this->db->select('p.*, pt.preroll_type_name, pt.preroll_type_description, ps.preroll_series_name');
        $this->db->from('prerolls p');
        $this->db->where('p.id', $id);
        $this->db->join('preroll_type pt', 'preroll_type_id=pt.id', 'left outer');
        $this->db->join('preroll_series ps', 'preroll_series_id=ps.id', 'left outer');

        $result = $this->db->get();

        return $result->row_array();
    }

    public function updatePreroll($id, $qd) {

        $this->db->where('id', $id);
        $this->db->update('prerolls', $qd);
    }

    public function getPrerollTypes() {

        $this->db->select('*');
        $this->db->from('preroll_type');

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getPrerollSeries() {

        $this->db->select('*');
        $this->db->from('preroll_series');

        $result = $this->db->get();

        return $result->result_array();
    }

}
