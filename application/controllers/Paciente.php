
<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Paciente extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
    	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    	header("Access-Control-Allow-Origin: *");
        // Load User Model
        $this->load->model('doctor_model', 'DoctorModel');
        $this->load->model('paciente_model', 'PacienteModel');
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
     * get data paciente
     * ----------------------------------
     * @param: IDPaciente
     */
    public function getdata(){
        $respuesta=$this->where()->get();
    }

    //funcion para registrar un nuevo paciente
    public function savedatag_post(){
        header("Access-Control-Allow-Origin: *");
        
	    $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        
       
        // primero insertamos los datos principales para obtebner el id
        $_ID_Paciente=$this->PacienteModel->save_data(
            $_POST["Nombre"],
            $_POST["ApellidoMaterno"],
            $_POST["ApellidoPaterno"],
            $_POST["FechaNacimiento"],
            $_POST["Edad"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["Email"],
            $_POST["ExpedienteAnteriro"],
            'S/Foto',
            $_POST["Telefono"],
           $_POST["Movil"]
        );
        
        // ahora guardo la direccion del paciente
        $output=$this->PacienteModel->save_direccion(
        $_ID_Paciente,
        $_POST["Calle"],
        $_POST["Colonia"],
        $_POST["Municipio"],
        $_POST["Estado"],
        $_POST["Codigo_Postal"]
        );
            // ahora guardo la direccion del paciente
        $output = $this->PacienteModel->save_data_emergencias(
        $_ID_Paciente,
        $_POST["Nombre_Emergecia"],
        $_POST["Apellidos_Emergecia"],
        $_POST["Parentesco_Emergecia"],
        $_POST["Email_Emergecia"],
        $_POST["Telefono_Emergecia"],
        $_POST["Movil_Emergecia"]
        );
        
            if ($output > 0 || !empty($output) || $output===NULL)
            {
                // Success 200 Code Send
                $message = [
                    'status' => true,
                    'message' => "Registro Correcto",
                    'data'=>$_POST,
                    'id'=>$_ID_Paciente

                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else
            {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Paciente No Registrado."
                ];
                
            }
    }

    public function changefoto_post(){
        $datos=$this->post();
       	if(count($_FILES)!==0){
				$_Imagen=$_FILES["logo"]["name"];	
				$ruta='./assets/img/fotospacientes/';
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
                    $this->PacienteModel->update_foto($datos["IDPaciente"],$nombreactual);
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

    // funcion para obtener todos los pacientes registrados
    public function getall_get(){
     $lista_pacientes=$this->PacienteModel->getall(); 
    $message = [
                'status' => true,
                'message' => $lista_pacientes,

            ];
	$this->response($message, REST_Controller::HTTP_OK);
    }
}