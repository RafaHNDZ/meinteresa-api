<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 3/02/18
 * Time: 04:55 PM
 */
class Usuario_Model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    public function save($user){
        if($this->db->insert('users', $user)){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    public function get_user_profile($user){
        $this->db->select('u.*, p.nombre as pais, e.abrev as estado, m.nombre as municipio');
        $this->db->from('users u');
        $this->db->join('paises p', 'p.pais_id = u.pais');
        $this->db->join('estados e', 'e.estado_id = u.estado');
        $this->db->join('municipios m', 'm.municipio_id = u.ciudad');
        $this->db->where('u.user_id', $user);
        $query = $this->db->get('users');
        if($query->num_rows() > 0){
          return $query->row();
        }else{
            return false;
        }
    }

    public function update($id, $data){
        $this->db->where('user_id', $id);
        if($this->db->update('users', $data)){
            return true;
        }else{
            return false;
        }
    }

    public function login($email){
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function valid($id){
        $this->db->where('user_id', $id);
        $query = $this->db->get('users');
        if($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function valid_token($token){
        $this->db->select('email');
        $this->db->where('token', $token);
        $query = $this->db->get('users');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }
}