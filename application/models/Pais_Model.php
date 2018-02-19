<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 2/02/18
 * Time: 01:50 PM
 */

class Pais_Model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    public function check_if_exist($pais){
        $this->db->select('*');
        $this->db->from('paises');
        $this->db->where('pais_id', $pais);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function get_details($id){
        $this->db->where('pais_id', $id);
        $query = $this->db->get('paises');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return null;
        }
    }
}