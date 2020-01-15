<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require  APPPATH.'/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';
use Restserver\libraries\REST_Controller;
/**
 * 
 */
class Doctor extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
        $this->load->model('Model_Doctor');
        $this->load->model('Model_User');
        $this->load->library('Authorization_Token');
         
        if(!isset($this->authorization_token->userData()->ID_Usuario)){
            $message = [
                'status' => FALSE,
                'message' => "Sesion No valida"
            ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }
     public function getall_get(){
     $lista_pacientes=$this->Model_Doctor->getall(); 
    
        $message = [
                    'status' => true,
                    'message' => $lista_pacientes,

                ];
        $this->response($message, REST_Controller::HTTP_OK);
    }
    // funcion para guardar losd datos de un doctor
    public function savedata_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);

        $ultimo_ID=$this->Model_Doctor->save_data(
        $_POST["Nombre"],
        $_POST["ApellidoPaterno"],
        $_POST["ApellidoMaterno"],
        $_POST["FechaNacimiento"],
        $_POST["Edad"],
        $_POST["Sexo"],
        $_POST["Curp"],
        $_POST["RFC"],
        $_POST["Telefono"],
        $_POST["Movil"],
        $_POST["Email"],
        $_POST["Unidad_Medica"],
        $_POST["Especialidad"],
        $_POST["Cedula"],
        $_POST["Institucion_Cedula"],
        $_POST["Especialidades"],
        $_POST["Dias_Consulta"]
        );

        //funcion para generar un usuarios y una clave;
        $clave=generate_clave();
        $this->Model_User->add_user_login($_POST["Email"],$clave,'1',$ultimo_ID);
        // funcion para mandar el correo con la clave

        $message = [
                    'status' => true,
                    'message' => "Registro Correcto",
                    'data'=>$_POST,
                    'id'=>$ultimo_ID

        ];
        $this->response($message, REST_Controller::HTTP_OK);
           
    }

    public function changefoto_post(){
        $datos=$this->post();
       	if(count($_FILES)!==0){
				$_Imagen=$_FILES["logo"]["name"];	
				$ruta='./assets/img/fotosdoctores/';
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
                    $this->Model_Doctor->update_foto($datos["IDDoctor"],$nombreactual);
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