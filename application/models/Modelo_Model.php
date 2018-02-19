<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 15/02/18
 * Time: 11:53 AM
 */
class Modelo_Model extends CI_Model{

    public function getAll(){
        $query = $this->db->get('modelos');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    public function getModelos($fabricante){
        $this->db->where('fabricante', $fabricante);
        $query = $this->db->get('modelos');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
}