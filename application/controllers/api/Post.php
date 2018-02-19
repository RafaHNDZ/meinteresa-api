<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 14/02/18
 * Time: 12:05 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Post extends REST_Controller{

    function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, 'Authorization");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('Usuario_Model', 'Usuario');
        $this->load->model('Post_Model', 'Post');
        $this->load->library('form_validation');
    }

    public function index_post(){
        $user_id = $this->input->get('user_id');
        $token = $this->input->get('token');
        if(!$user_id OR !$token){
            $response = array(
                'error' => true,
                'message' => 'Se requiere ID de usuario y/o token'
            );
        }else{
            if(!$this->Usuario->valid($user_id)){
                $response = array(
                    'error' => true,
                    'message' => 'Usuario no encontrado'
                );
            }else{
                $this->form_validation->set_rules('modelo', 'Modelo', 'required');
                $this->form_validation->set_rules('fabricante', 'Fabricante', 'required');
                $this->form_validation->set_rules('conservacion', 'Conservacion', 'required');
                $this->form_validation->set_rules('motor', 'Motor', 'required');
                $this->form_validation->set_rules('kilometraje', 'Kilometraje', 'required');
                $this->form_validation->set_rules('combustible', 'Combustible', 'required');
                $this->form_validation->set_rules('transmision', 'Transmision', 'required');
                $this->form_validation->set_rules('puertas', 'Numero de puertas', 'required');
                $this->form_validation->set_rules('asientos', 'Numero de asientos', 'required');
                //$this->form_validation->set_rules('color', 'Color', 'required');
                $this->form_validation->set_rules('estado', 'Estado', 'required');
                $this->form_validation->set_rules('ciudad', 'Ciudad', 'required');

                if($this->form_validation->run() === false){
                    $response = array(
                        'error' => true,
                        'message' => $this->validation_errors()
                    );
                }else{
                    $files = $this->reArrayFiles($_FILES['files']);
                    if(!$files){
                        $response = array(
                            'error' => true,
                            'message' => 'Selecciona almenos una imagen'
                        );
                    }else{
                        //Preparar objeto Post para insertar en la DB
                        $post = array(
                            'user' => $this->input->get('user_id'),
                            'fabricante' => $this->post('fabricante'),
                            'modelo' => $this->post('modelo'),
                            'motor' => $this->post('motor'),
                            'kilometraje' => $this->post('kilometraje'),
                            'transmision' => $this->post('transmision'),
                            'noPuertas' => $this->post('puertas'),
                            'noAsientos' => $this->post('asientos'),
                            //'color' => $this->post('color'),
                            'estado' => $this->post('estado'),
                            'ciudad' => $this->post('ciudad')
                        );
                        $this->load->model('File_Model', 'File');
                        $this->load->library('upload');
            
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
                                    'uploadedBy' => $user_id
                                );
                                $file_id = $this->File->save($file);
                                if($file_id){
                                    array_push($completes, $file_id);
                                }else{
                                    array_push($errors, $file);
                                }
                            }
                        }
                        if(!$this->Post->add($post, $completes)){
                            $response = array(
                                'error' => true,
                                'message' => 'Error al registrar la publicacion'
                            );
                        }else{
                            $response = array(
                                'error' => false
                            );
                        }
                    }
                }
            }
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