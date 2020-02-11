<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require  APPPATH.'/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';
use Restserver\libraries\REST_Controller;
/**
 * 
 */
class Citas extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
      $this->load->model('Model_Citas');
        $this->load->library('Authorization_Token');
         
        if(!isset($this->authorization_token->userData()->ID_Usuario)){
            $message = [
                'status' => FALSE,
                'message' => "Sesion No valida"
            ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }

     public function save_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos_paciente=explode("|",$_POST["paciente"]);
       $respuesta= $this->Model_Citas->save($datos_paciente[0],$datos_paciente[1],$_POST["doctor"],$_POST["fecha"],$_POST["hora"],$_POST["comentarios"]);
       if($respuesta){
            $message = [
                    'status' => true,
                    'data' => $_POST

            ];
        $this->response($message, REST_Controller::HTTP_OK);
       }else{
           $message = [
                'status' => FALSE,
                'message' => "Fallo al guardar cita"
            ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }
    public function getcitasdate_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        
        $inicio=str_replace("/","-",$_POST['inicio']);
        $fin=str_replace("/","-",$_POST['fin']);
        
        $inicio = new DateTime($inicio); 
        $fin = new DateTime($fin);
        
        $respuesta= $this->Model_Citas->getdatecitas($inicio->format('Y-m-d'),$fin->format('Y-m-d'));
        $message = [
                    'status' => true,
                    'data' => $respuesta

            ];
        $this->response($message, REST_Controller::HTTP_OK);
    }
}