<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 28/01/18
 * Time: 09:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, 'Authorization");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Usuario_Model', 'Usuario');
        //$this->load->model('Pais_Model', 'Pais');
        //$this->load->model('Estado_Model', 'Estado');
        //$this->load->model('Municipio_Model', 'Municipio');
    }

    public function register_post(){
        $this->form_validation->set_rules('name', 'Nombre', 'required|trim');
        $this->form_validation->set_rules('email', 'Correo', 'required|trim|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|trim');
        $this->form_validation->set_rules('type', 'Tipo de cuenta', 'required');
        $this->form_validation->set_rules('dob', 'Fecha de nacimiento','required');
        $this->form_validation->set_rules('gener', 'Genero','required');
        $this->form_validation->set_rules('phone', 'Telefono','required');
        $this->form_validation->set_rules('pais', 'Pais','required');
        $this->form_validation->set_rules('estado', 'Estado','required');
        $this->form_validation->set_rules('ciudad', 'Ciudad','required');
        if($this->form_validation->run() === FALSE){
            $response = array(
                'error' => true,
                'form_errors' => $this->validation_errors('<p>','</p>')
            );
        }else{
            $user = array(
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'password' => password_hash($this->post('password'), PASSWORD_DEFAULT, ['cost' => 14]),
                'type' => $this->post('type'),
                'dob' => $this->post('dob'),
                'phone' => $this->post('phone'),
                'profile_pic' => '',
                'gener' => $this->post('gener'),
                'pais' => $this->post('pais'),
                'estado' => $this->post('estado'),
                'ciudad' => $this->post('ciudad')
            );
            $uId = $this->Usuario->save($user);
            if(!$uId){
                $response = array(
                    'error' => true,
                    'message' => 'No se ha podido registrar al usuario'
                );
            }else{
                $response = array(
                    'error' => false,
                    'user_id' => $uId
                );
            }
        }
        $this->response($response);
    }

    public function login_post(){
        if(!$this->post('email') or !$this->post('password')){
            $response = array(
                'error' => true,
                'message' => 'Correo y/o Contraseña requeridos'
            );
        }else{
            $email = $this->post('email');
            $password = $this->post('password');
            $user = $this->Usuario->login($email);
            if(!$user){
                $response = array(
                    'error' => true,
                    'message' => 'No se encontro ese usuario'
                );
            }else{
                $hash = $user->password;
                if(!password_verify($password, $hash)){
                    $user = null;
                    $response = array(
                        'error' => true,
                        'message' => 'Contraseña incorrecta'
                    );
                }else{
                    $token = $this->encryption->encrypt($user->email);
                    if(!$this->Usuario->update($user->user_id, array('token' => $token))){
                        $response = array(
                            'error' => true,
                            'message' => 'Token Error!'
                        );
                    }else{
                        $response = array(
                            'error' => false,
                            'user_id' => $user->user_id,
                            'token' => $token
                        );
                    }
                }
            }
        }
        $this->response($response);
    }

    public function user_get($user_id = null){
        /**
        $headers = getallheaders();
        if(!isset($headers['Authorization'])){
            $response = array(
                'error' => true,
                'message' => 'Se requiere token de autorización'
            );
        }else{
            $token = $headers['Authorization'];
            if(!$this->Usuario->valid_token($token)){
                $response = array(
                    'error' => true,
                    'message' => 'Token invalido'
                );
            }else{

            }
        }**/
        if(!$user_id){
            $response = array(
                'error' => true,
                'message' => 'Se requiere ID de usuario'
            );
        }else{
            $user = $this->Usuario->get_user_profile($user_id);
            if(!$user){
                $response = array(
                    'error' => true,
                    'message' => 'Usuario no encontrado'
                );
            }else{
                unset($user->password);
                unset($user->token);
                $response = array(
                    'error' => false,
                    'user' => $user
                );
                $secret = md5(uniqid($user->user_id, true));
                //array_push($response, $secret);
            }
        }
        $this->response($response);
    }

    public function users_get(){

    }

    public function update_put($user_id = null){
        $headers = getallheaders();
        if(!isset($headers['Authorization'])){
            $response = array(
                'error' => true,
                'message' => 'Se requiere token de autorización'
            );
        }else{
            $token = $headers['Authorization'];
            $hash = $this->Usuario->valid_token($token);
            if(!$hash){
                $response = array(
                    'error' => true,
                    'message' => 'Token invalido'
                );
            }else{
                if(!$user_id){
                    $response = array(
                        'error' => true,
                        'message' => 'Se requiere ID de usuario'
                    );
                }else{
                    if(!$this->Usuario->valid($user_id)){
                        $response = array(
                            'error' => true,
                            'message' => 'Usuario no encontrado'
                        );
                    }else{
                        $data = $this->put();
                        if(empty($data)){
                            $response = array(
                                'error' => true,
                                'message' => 'No se han recibido datos'
                            );
                        }else{
                            if(!$this->Usuario->update($user_id, $data)){
                                $response = array(
                                    'error' => true,
                                    'message' => 'No se ha actualizado el registro'
                                );
                            }else{
                                $response = array(
                                    'error' => false,
                                    'message' => 'Registro actualizado'
                                );
                            }
                        }
                    }
                }
            }
        }
        $this->response($response);
    }

    public function user_delete(){

    }
}