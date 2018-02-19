<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 14/02/18
 * Time: 04:24 PM
 */

class Fabricante_Model extends CI_Model{
    public function get(){
        $fabricantes = $this->db->get('fabricantes');
        if($fabricantes->num_rows() > 0){
            return $fabricantes->result();
        }else{
            return false;
        }
    }
}