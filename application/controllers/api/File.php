<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 3/02/18
 * Time: 09:21 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class File  extends  REST_Controller {

    function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('File_Model', 'File');
    }

    public function upload_post(){
        if(!$this->post('uploader')){
            $response = array(
                'error' => true,
                'message' => 'Se requiere ID del creador del archivo'
            );
        }else{
            $this->load->library('upload');
            $files = $this->reArrayFiles($_FILES['files']);

            $errors = array();
            $completes = array();

            foreach ($files as $key => $fileObject){
                $_FILES['upload']['name'] = $fileObject['name'];
                $_FILES['upload']['type'] = $fileObject['type'];
                $_FILES['upload']['tmp_name'] = $fileObject['tmp_name'];
                $_FILES['upload']['error'] = $fileObject['error'];
                $_FILES['upload']['size'] = $fileObject['size'];
                $config = array(
                    'allowed_types' => 'jpg|jpeg|png|gif',
                    'max_size' => 3000,
                    'overwrite' => FALSE,
                    'upload_path' => './public/uploads/'
                );

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('upload')) {
                    array_push($errors, $this->upload->display_errors());
                } else {
                    $file =array(
                        'nombre' => $this->upload->data('file_name'),
                        'path' => $this->upload->data('file_path'),
                        'uploadedBy' => $this->post('uploader')
                    );
                    $file_id = $this->File->save($file);
                    if($file_id){
                        array_push($completes, $file_id);
                    }else{
                        array_push($errors, $file);
                    }
                }
            }
            $response = array(
                'errors' => $errors,
                'uploads' => $completes,
                'uploader' => $this->post('uploader')
            );
        }
        $this->response($response);
    }

    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

}