<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 1/02/18
 * Time: 11:34 PM
 */

class Estado_Model extends CI_Model{

    public function getEstados($pais){
        $this->db->select('*');
        $this->db->from('estados');
        $this->db->where('pais', $pais);
        $this->db->order_by('nombre');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return null;
        }
    }

    public function get_details($id){
        $this->db->where('estado_id', $id);
        $query = $this->db->get('estados');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }
}