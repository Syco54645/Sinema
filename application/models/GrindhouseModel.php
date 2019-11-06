<?php
class GrindhouseModel extends MY_Model {

    public function storeGrindhouse($qd) {

        $this->db->insert('grindhouse', $qd);
        return $this->db->insert_id();
    }

    public function getGrindhouses($options = []) {

        $this->db->select('g.*');
        $this->db->from('grindhouse g');

        if (isset($options['typeId'])) {
            $this->db->where('preroll_type_id', $options['typeId']);
        }
        if (isset($options['limit']) && $options['limit'] != null) {
            if ($options['offset'] > 0) {
                $this->db->limit($options['limit'], $options['offset']);
            } else {
                $this->db->limit($options['limit']);
            }
        }

        if (isset($options['orderBy'])) {
            $this->db->order_by($options['orderBy']['field'], $options['orderBy']['direction']);
        }

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getGrindhouseById($id) {

        $this->db->select('*');
        $this->db->from('grindhouse');
        $this->db->where('id', $id);

        return $this->db->get()->row_array();
    }

    public function updateGrindhouse($id, $qd) {

        $this->db->where('id', $id);
        $this->db->update('grindhouse', $qd);
    }

}
