<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 5/02/18
 * Time: 08:49 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Encryption {

    private $method;
    protected $secret;

    function __construct(){
        $this->method = 'SHA-256';
        $this->secret = $this->config->item('encryption_key');
    }

    public function encrypt($data){
        if($data){
            //return base64_encode(openssl_encrypt($data, $this->method, $this->secret, $encoded = false, $iv = '', $tag = '', $add = ''));
            $jwt = JWT::encode($data, $this->secret, 'RS256');
            return $jwt;
        }else{
            return null;
        }
    }

    public function decrypt($data){
        if($data){
            //return openssl_decrypt(base64_decode($data), $this->method, $this->secret, $encoded = false);
            return JWT::decode($data, $this->secret, 'RS256');
        }else{
            return null;
        }
    }

    public function match($data, $hash){
        $uncripted_hash = self::decrypt($hash);
        $uncripted_data = self::decrypt($data);
        if($uncripted_data === $uncripted_hash){
            return true;
        }else{
            return false;
        }
    }
}