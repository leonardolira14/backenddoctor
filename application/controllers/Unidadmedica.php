
<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Unidadmedica extends REST_Controller
{
     protected $cadena_usuario= 'pumdf';
    
     public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
        $this->load->model('Model_Doctor');
        $this->load->model('Model_Paciente');
         $this->load->model('Model_Unidad');
        $this->load->library('Authorization_Token');
         
        if(!isset($this->authorization_token->userData()->ID_Usuario)){
            $message = [
                'status' => FALSE,
                'message' => "Sesion No valida"
            ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }

      /**
     * save Unidad Medica 
     *
     * ----------------------------------
     * @param: Nombre
     * @param: Razon Social
     * @param: Administrativo Responsable
     * @param: Doctor Responsable
     * @param: Personal de Contacto
     * @param: Email
     * @param: Telefono fijo 1
     * @param: Telefono fijo 2
     * @param: Estado
     * @param: Codigo postal
     * @param: Calle y Numero
     * 
     */

     public function save_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_ID=$this->Model_Unidad->save(
                $_POST["Nombre"],
                $_POST["Razon_Social"],
                $_POST["Administrativo_Responsable"],
                $_POST["Doctor_Responsable"],
                $_POST["Personal_Contacto"],
                $_POST["Email"],
                $_POST["Telefono1"],
                $_POST["Telefono2"],
                $_POST["Estado"],
                $_POST["Municipio"],
                $_POST["CodigoPostal"],
                $_POST["CalleNumero"],
                 $_POST["Ciudad"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_ID
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }

      /**
     * save Unidad Medica 
     *
     * ----------------------------------
     * @param: IDUnidadMedica
     * @param: Nombre
     * @param: Razon Social
     * @param: Administrativo Responsable
     * @param: Doctor Responsable
     * @param: Personal de Contacto
     * @param: Email
     * @param: Telefono fijo 1
     * @param: Telefono fijo 2
     * @param: Estado
     * @param: Codigo postal
     * @param: Calle y Numero
     * 
     */

     public function update_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
         $this->Model_Unidad->update(
                $_POST["IDUnidad"],
                $_POST["Nombre"],
                $_POST["Razon_Social"],
                $_POST["Administrativo_Responsable"],
                $_POST["Doctor_Responsable"],
                $_POST["Personal_Contacto"],
                $_POST["Email"],
                $_POST["Telefono1"],
                $_POST["Telefono2"],
                $_POST["Estado"],
                $_POST["Municipio"],
                $_POST["CodigoPostal"],
                $_POST["CalleNumero"],
                $_POST["Ciudad"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_POST
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }


     public function getData_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_Data=$this->Model_Unidad->getdata(
                $_POST["IDUnidad"]
                
          );

          // mando la lista de los doctores
          $_Data_doctores=$this->Model_Doctor->getall();
          
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data,
                'Doctores'=>$_Data_doctores
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }
     public function getall_get(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_Data=$this->Model_Unidad->getall();
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }

     public function busqueda_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_Data=$this->Model_Unidad->busqueda(
              $_POST["Palabra"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }

     // funion para cambiar el status de una unidad
     public function bajaunidad_post(){
         $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
            $this->Model_Unidad->updatestatus(
              $_POST["IDUnidad"],
              $_POST["Status"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }
}
