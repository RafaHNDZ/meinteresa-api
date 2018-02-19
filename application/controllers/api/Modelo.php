<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 15/02/18
 * Time: 11:54 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Modelo extends REST_Controller{

    function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, 'Authorization");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('Modelo_Model', 'Modelo');
    }

    public function index_get($fabricante = null){
        //$fabricante = $this->input->get('fabricante');
        if(!$fabricante){
            $modelos = $this->Modelo->getAll();
            if(!$modelos){
                $response = array(
                    'error' => true,
                    'message' => 'Sin modelos registrados'
                );
            }else{
                $response = array(
                    'error' => false,
                    'modelos' => $modelos
                );
            }
        }else{
            $modelos = $this->Modelo->getModelos($fabricante);
            if(!$modelos){
                $response = array(
                    'error' => true,
                    'message' => 'Sin modelos registrados'
                );
            }else{
                $response = array(
                    'error' => false,
                    'modelos' => $modelos
                );
            }
        }
        $this->response($response);
    }
}