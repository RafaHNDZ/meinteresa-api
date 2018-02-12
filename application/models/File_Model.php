<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 4/02/18
 * Time: 11:07 PM
 */

class File_Model extends CI_Model{
    public function __construct(){

    }

    public function save($file){
        if($this->db->insert('archivos', $file)){
            return true;
        }else{
            return false;
        }
    }
}