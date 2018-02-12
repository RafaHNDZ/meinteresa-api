<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 2/02/18
 * Time: 03:13 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Municipio extends REST_Controller{
    function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('Municipio_Model', 'MM');
    }

    public function municipios_get($estado = null){
        if(!isset($estado)){
            $response = array(
                'error' => true,
                'message' => 'No se ha especificado un estado'
            );
        }else{
            $estados = $this->MM->getMunicipios($estado);
            if(!$estados){
                $response = array(
                    'error' => true,
                    'message' => 'No se han encontrado muicipios registrados'
                );
            }else{
                $response = array(
                    'error' => false,
                    'ciudades' => $estados
                );
            }
        }
        $this->response($response);
    }
}