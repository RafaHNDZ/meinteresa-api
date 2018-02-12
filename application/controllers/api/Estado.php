<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 1/02/18
 * Time: 11:31 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Estado extends REST_Controller{

    public function __construct(){
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");
        parent::__construct();
        $this->load->model('Estado_Model', 'EM');
        $this->load->model('Pais_Model', 'PM');
    }

    public function estados_get($pais = null){
        if(!$pais){
            $response = array(
                'error' => true,
                'message' => 'No se ha especificado un pais'
            );
        }else{
            if(!$this->PM->check_if_exist($pais)){
                $response = array(
                    'error' => true,
                    'message' => 'Ese pais no esta registrado'
                );
            }else{
                $estados = $this->EM->getEstados($pais);
                if(!$estados){
                    $response = array(
                        'error' => true,
                        'menssage' => 'No se han encontrado estados para ese pais'
                    );
                }else{
                    $response = array(
                        'error'=> false,
                        'estados'=> $estados
                    );
                }
            }

        }
        $this->response($response);
    }
}