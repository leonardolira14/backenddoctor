<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Asistentemedico extends REST_Controller
{
     protected $cadena_usuario= 'pumdf';
    
     public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
        $this->load->model('Model_User');
        $this->load->model('Model_Asistentemedico');
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
        $ultimo_ID=$this->Model_Asistentemedico->save( 
            $_POST["Nombre"],
            $_POST["Apellidos"],
            $_POST["FechaNacimiento"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["RFC"],
            $_POST["Telefono"],
            $_POST["Movil"],
            $_POST["Email"],
            $_POST["Unidad_Medica"],
            $_POST["Horario"],
            $_POST["Comentarios"],
            $_POST["Departamento"]
        );
        $clave=generate_clave();
        $this->Model_User->add_user_login($_POST["Email"],$clave,'4',$ultimo_ID);
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
        $_Data=$this->Model_Asistentemedico->update( 
            $_POST["IDAsistente"],
            $_POST["Nombre"],
            $_POST["Apellidos"],
            $_POST["FechaNacimiento"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["RFC"],
            $_POST["Telefono"],
            $_POST["Movil"],
            $_POST["Email"],
            $_POST["Unidad_Medica"],
            $_POST["Horario"],
            $_POST["Comentarios"],
            $_POST["Departamento"]
        );
        // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);
    
    }
    public function buscar_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $respuesta=$this->Model_Asistentemedico->busqueda($_POST["Palabra"]);
        // Success 200 Code Send
        $message = [
                'status' => true,
                'data'=>$respuesta
        ];
        $this->response($message, REST_Controller::HTTP_OK);

    }
    public function baja_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $this->Model_Asistentemedico->update_status($_POST["IDAsistente"],$_POST["Status"]);
        // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_POST
            ];
            $this->response($message, REST_Controller::HTTP_OK);

    }
    public function getall_get(){
       
        $_Data=$this->Model_Asistentemedico->getdata();
        // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);

    }
    public function getdata_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $_Data=$this->Model_Asistentemedico->getdata(
                True,
                $_POST["IDAsistente"]
                
          );
        // Success 200 Code Send
            $message = [
                'status' => true,
                'data'=>$_Data
            ];
            $this->response($message, REST_Controller::HTTP_OK);


    }
    public function changefoto_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos=$this->post();
        if(count($_FILES)!==0){
				$_Imagen=$_FILES["logo"]["name"];	
				$ruta='./assets/img/fotosasistente/';
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
                   $this->Model_Asistentemedico->update_foto($datos["IDAsistente"],$nombreactual);
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