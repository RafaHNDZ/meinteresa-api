<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 14/02/18
 * Time: 12:09 PM
 */

class Post_Model extends CI_Model{

    public function add($post, $files){
        $this->db->trans_begin();
        $this->db->insert('posts', $post);
        $post_id = $this->db->insert_id();
        foreach($files as $file){
            $galeria = array(
                'post' => $post_id, 
                'file' => $file
            );
            $this->db->insert('post_gallery', $galeria);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }
}