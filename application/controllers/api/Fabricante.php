<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 14/02/18
 * Time: 04:22 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Fabricante extends REST_Controller{
    function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, 'Authorization");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('Fabricante_Model', 'Fabricante');
    }
    public function index_get(){
        $fabricantes = $this->Fabricante->get();
        if(!$fabricantes){
            $response = array(
                'error' => true,
                'message' => 'Sin fabricantes registrados'
            );
        }else{
            $response = array(
                'error' => false,
                'fabricantes' => $fabricantes
            );
        }
        $this->response($response);
    }
}