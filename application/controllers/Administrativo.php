<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Administrativo extends REST_Controller
{
     protected $cadena_usuario= 'pumdf';
    
     public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
        $this->load->model('Model_User');
        $this->load->model('Model_Administrativo');
        $this->load->library('Authorization_Token');
         
        if(!isset($this->authorization_token->userData()->ID_Usuario)){
            $message = [
                'status' => FALSE,
                'message' => "Sesion No valida"
            ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }
      
// funcion para guardar losd datos de un doctor
    public function savedata_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $ultimo_ID=$this->Model_Administrativo->save_data(
            $_POST["Nombre"],
            $_POST["Apellidos"],
            $_POST["FechaNacimiento"],
            $_POST["Edad"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["RFC"],
            $_POST["Telefono"],
            $_POST["Movil"],
            $_POST["Email"],
            $_POST["Unidad_Medica"],
            $_POST["Departamento"],
            $_POST["Funciones"],
            $_POST["Horario"]
        );

        //funcion para generar un usuarios y una clave;
        $clave=generate_clave();
        $this->Model_User->add_user_login($_POST["Email"],$clave,'3',$ultimo_ID);
        // funcion para mandar el correo con la clave

        $message = [
                    'status' => true,
                    'message' => "Registro Correcto",
                    'data'=>$_POST,
                    'id'=>$ultimo_ID

        ];
        $this->response($message, REST_Controller::HTTP_OK);
           
    }

      

     public function update_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $this->Model_Administrativo->update_data(
            $_POST["IDAdministrativo"],
            $_POST["Nombre"],
            $_POST["Apellidos"],
            $_POST["FechaNacimiento"],
            $_POST["Edad"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["RFC"],
            $_POST["Telefono"],
            $_POST["Movil"],
            $_POST["Email"],
            $_POST["Unidad_Medica"],
            $_POST["Departamento"],
            $_POST["Funciones"],
            $_POST["Horario"]
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
          $_Data=$this->Model_Administrativo->getdata(
                $_POST["IDAdministrativo"]
                
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }
     public function getDataAll_get(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_Data=$this->Model_Administrativo->getall();
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
          $_Data=$this->Model_Administrativo->busqueda(
              $_POST["Palabra"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }
      public function baja_post(){
          $_POST = json_decode(file_get_contents("php://input"), true);
          $_POST = $this->security->xss_clean($_POST);
          $_Data=$this->Model_Administrativo->update_status(
              $_POST["IDAdministrativo"],
              $_POST["Status"]
          );
          // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
     }
    
    public function changefoto_post(){
        $datos=$this->post();
       	if(count($_FILES)!==0){
				$_Imagen=$_FILES["logo"]["name"];	
				$ruta='./assets/img/fotosadministrativo/';
				$rutatemporal=$_FILES["logo"]["tmp_name"];
				$nombreactual=$_FILES["logo"]["name"];
				try {
					if(! move_uploaded_file($rutatemporal, $ruta.$nombreactual)){
                        $message = [
                            'status' => FALSE,
                            'message' => "No se puede subir imagen."
                        ];
						$this->response($message, REST_Controller::HTTP_NOT_FOUND);
					}	
                    // Success 200 Code Send
                    $this->Model_Administrativo->update_foto($datos["IDAdministrativo"],$nombreactual);
                    $message = [
                        'status' => true,
                        'message' => "Exito",

                    ];
					$this->response($message, REST_Controller::HTTP_OK);
								
				} catch (Exception $e) {
                    $message = [
                            'status' => FALSE,
                            'message' => $e->getMessage()
                        ];
						$this->response($message, REST_Controller::HTTP_NOT_FOUND);
						
				}
			}
        
    }
}