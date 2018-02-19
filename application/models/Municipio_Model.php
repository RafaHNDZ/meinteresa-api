<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 2/02/18
 * Time: 02:59 PM
 */
class Municipio_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    public function getMunicipios($estado){
        $this->db->select('*');
        $this->db->from('municipios');
        $this->db->where('estado', $estado);
        $this->db->order_by('nombre');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return null;
        }
    }

    public function get_details($id){
        $this->db->where('municipio_id', $id);
        $query = $this->db->get('municipios');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }
}