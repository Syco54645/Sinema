<?php
class GrindhouseModel extends MY_Model {

    public function storeGrindhouse($qd) {

        $this->db->insert('grindhouse', $qd);
        return $this->db->insert_id();
    }
}
