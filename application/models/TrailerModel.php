<?php
class TrailerModel extends MY_Model {

    public function getTrailers($options = []) {

        $this->db->select('t.*');
        $this->db->from('trailers t');

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

    public function storeTrailer($qd) {

        $this->db->insert('trailers', $qd);
        return $this->db->insert_id();
    }

    public function getTrailerById($id) {

        $this->db->select('*');
        $this->db->from('trailers');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function checkTrailerExists($id) {

        $this->db->select('*');
        $this->db->from('trailers');
        $this->db->where('id', $id);

        return $this->db->count_all_results();
    }

    public function updateTrailer($id, $qd) {

        $this->db->where('id', $id);
        $this->db->update('trailers', $qd);
    }
}
