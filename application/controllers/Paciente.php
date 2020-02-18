
<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Paciente extends REST_Controller
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
    public function getdata_post(){
         $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
         $datos=$this->post();
         $data["datos_paciente"]=$this->Model_Paciente->getdata_by_ID($datos["IDPaciente"]);
         $data["direccion_paciente"]=$this->Model_Paciente->getdress_by_ID($datos["IDPaciente"]);
         $data["datos_emergencia"]=$this->Model_Paciente->getemergenci_by_ID($datos["IDPaciente"]);
         $data["datos_discapacidad"]=$this->Model_Paciente->getdatadiscapacidad_by_ID($datos["IDPaciente"]);
         // Success 200 Code Send
            $message = [
                    'status' => true,
                    'data'=>$data,
            ];
            $this->response($message, REST_Controller::HTTP_OK);
    }

    //funcion para registrar un nuevo paciente
    public function savedatag_post(){
        header("Access-Control-Allow-Origin: *");
        
	    $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        
       if($_POST["Discapacidad"]==='1'){
            $Discapacidad = '1'; 
       }else{
           $Discapacidad='0';
       }
        // primero insertamos los datos principales para obtebner el id
        $_ID_Paciente=$this->Model_Paciente->save_data(
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
           $_POST["Movil"],
           $Discapacidad,
           $_POST["Grupo_Sanguineo"],
           $_POST["Religion"],
           $_POST["Ocupacion"]
        );
        // hora veo si es disapacitado o no
        if($_POST["Discapacidad"]==='1'){
           $output=$this->Model_Paciente->savediscapacidad(
               $_ID_Paciente,
               $_POST["Nombre_Discapacidad"],
               $_POST["Apellidos_Discapacidad"],
               $_POST["Parentesco_Discapacidad"],
               $_POST["Causa_Discapacidad"]
            );
        }
        // ahora guardo la direccion del paciente
        $output=$this->Model_Paciente->save_direccion(
        $_ID_Paciente,
        $_POST["Calle"],
        $_POST["Colonia"],
        $_POST["Municipio"],
        $_POST["Estado"],
        $_POST["Codigo_Postal"]
        );
            // ahora guardo la direccion del paciente
        $output = $this->Model_Paciente->save_data_emergencias(
        $_ID_Paciente,
        $_POST["Nombre_Emergecia"],
        $_POST["Apellidos_Emergecia"],
        $_POST["Parentesco_Emergecia"],
        $_POST["Email_Emergecia"],
        $_POST["Telefono_Emergecia"],
        $_POST["Movil_Emergecia"]
        );
        //funcion para generar un usuarios y una clave;
        $clave=generate_clave();
        $this->Model_User->add_user_login(tr_replace(" ","",$_POST["Nombre"])."-".$_ID_Paciente."-".$this->cadena_usuario,$clave,'2',$_ID_Paciente);
        // aqui vamos a mandar el correo
        
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
                    $this->Model_Paciente->update_foto($datos["IDPaciente"],$nombreactual);
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
     $lista_pacientes=$this->Model_Paciente->getall(); 
    
        $message = [
                    'status' => true,
                    'message' => $lista_pacientes,

                ];
        $this->response($message, REST_Controller::HTTP_OK);
    }
    
    // funcion para actualizar los datos de un paciente
    public function updatedatap_post(){        
	    $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        if($_POST["Discapacidad"]==='1'){
            $Discapacidad = '1'; 
       }else{
           $Discapacidad='0';
       }
        // primero insertamos los datos principales para obtebner el id
        $this->Model_Paciente->update_data(
            $_POST["IDPaciente"],
            $_POST["Nombre"],
            $_POST["ApellidoMaterno"],
            $_POST["ApellidoPaterno"],
            $_POST["FechaNacimiento"],
            $_POST["Edad"],
            $_POST["Sexo"],
            $_POST["Curp"],
            $_POST["Email"],
            $_POST["ExpedienteAnteriro"],
            $_POST["Telefono"],
           $_POST["Movil"],
           $Discapacidad,
           $_POST["Grupo_Sanguineo"],
           $_POST["Religion"],
           $_POST["Ocupacion"]
        );
        // hora veo si es disapacitado o no
        if($_POST["Discapacidad"]==='1'){
           $output=$this->Model_Paciente->update_discapacidad(
               0,
               $_POST["IDPaciente"],
               $_POST["Nombre_Discapacidad"],
               $_POST["Apellidos_Discapacidad"],
               $_POST["Parentesco_Discapacidad"],
               $_POST["Causa_Discapacidad"]
            );
        }
        // ahora guardo la direccion del paciente
        $output=$this->Model_Paciente->update_direccion(
        $_POST["IDPaciente"],
        $_POST["Calle"],
        $_POST["Colonia"],
        $_POST["Municipio"],
        $_POST["Estado"],
        $_POST["Codigo_Postal"]
        );
            // ahora guardo la direccion del paciente
        $output = $this->Model_Paciente->update_data_emergencias(
        $_POST["IDPaciente"],
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
                    'message' => "Actualizacion Correcta",
                    'data'=>$_POST

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

    // funcion para cambiar el status de  un paciente
    public function updatestatus_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $output=$this->Model_Paciente->update_status($_POST["IDPaciente"],$_POST["status"]);
        if ($output > 0 || !empty($output) || $output===NULL)
            {
                // Success 200 Code Send
                $message = [
                    'status' => true,
                    'message' => "Actualizacion Correcta",
                    'data'=>$_POST

                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else
            {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Error al actualizar."
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
    }

     //funcion para cambiar el status de un doctor
    public function busqueda_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos=$this->post();
        $_data=$this->Model_Paciente->busqueda($datos["palabra"]);
        $message = [
                        'status' => true,
                        'message' => "Exito",
                        'data'=>$_data

                    ];
		$this->response($message, REST_Controller::HTTP_OK);
    }

    //funcion para actuazalizar los antecedentes de un paciente
    public function updateantecedentes_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos=$this->post();
        $_data=$this->Model_Paciente->update_antecedentes($datos["idpaciente"],$datos["antecedentes"]);
       if($_data){
            $message = [
                        'status' => true,
                        'message' => "Exito",
                        'data'=>$datos

                    ];
		$this->response($message, REST_Controller::HTTP_OK);
       }else{
            $message = [
                        'status' => false,
                        'message' => "Error",
                        'data'=>$datos

                    ];
		$this->response($message, REST_Controller::HTTP_NOT_FOUND);
       }
    }

    public function getantecedentes_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos=$this->post();
        $_data=$this->Model_Paciente->get_antecedentes($datos["idpaciente"]);
        $message = [
                        'status' => true,
                        'message' => "Exito",
                        'data'=>$_data

                    ];
		$this->response($message, REST_Controller::HTTP_OK);
       
    }

     public function savesocioeconomicop_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $_POST = $this->security->xss_clean($_POST);
        $datos=$this->post();
        

        // actualizo datos de discapacidad
           $output=$this->Model_Paciente->update_discapacidad(
               $_POST["datos_discapacidad"]["IDDiscapacidad"],
                $_POST["datos_paciente"]["IDPaciente"],
                $_POST["datos_discapacidad"]["Nombre"],
                $_POST["datos_discapacidad"]["Apellidos"],
                $_POST["datos_discapacidad"]["Parentesco"],
                $_POST["datos_discapacidad"]["Causa"],
                $_POST["datos_discapacidad"]["Sexo"],
                $_POST["datos_discapacidad"]["Edad"],
                $_POST["datos_discapacidad"]["Estado_Civil"],
                $_POST["datos_discapacidad"]["Escolaridad"],
                $_POST["datos_discapacidad"]["Ocupacion"],
                $_POST["datos_discapacidad"]["Telefono"],
                 $_POST["datos_discapacidad"]["Domicilio"]
            );
        // ahora los datos de direccion

        $output=$this->Model_Paciente->update_direccion(
                $_POST["datos_paciente"]["IDPaciente"],
                $_POST["direccion_paciente"]["Calle_Y_Numero"],
                $_POST["direccion_paciente"]["Colonia"],
                $_POST["direccion_paciente"]["Municipio"],
                $_POST["direccion_paciente"]["Estado"],
                $_POST["direccion_paciente"]["CP"]
        );
         $this->Model_Paciente->update_data(
            $_POST["datos_paciente"]["IDPaciente"],
          $_POST["datos_paciente"]["Nombre"],
           $_POST["datos_paciente"]["Apellido_Mat"],
            $_POST["datos_paciente"]["Apellido_Pat"],
            $_POST["datos_paciente"]["Fecha_Nacimiento"],
            $_POST["datos_paciente"]["Edad"],
           $_POST["datos_paciente"]["Sexo"],
           $_POST["datos_paciente"]["Curp"],
            $_POST["datos_paciente"]["Email"],
           $_POST["datos_paciente"]["NumExpedienteAnterior"],
            $_POST["datos_paciente"]["Telefono"],
           $_POST["datos_paciente"]["Movil"],
           $_POST["datos_paciente"]['Discapacitado'],
           $_POST["datos_paciente"]["Grupo_Sanguineo"],
           $_POST["datos_paciente"]["Religion"],
           $_POST["datos_paciente"]["Ocupacion"],
            $_POST["datos_paciente"]["Estado_Civil"],
            $_POST["datos_paciente"]["NodeControl"],
            $_POST["datos_paciente"]["Escolaridad"],
            $_POST["datos_paciente"]["Estructura_Familiar"],
            $_POST["datos_paciente"]["Egresos"],
            $_POST["datos_paciente"]["Vivienda"],
            $_POST["datos_paciente"]["Seguridad_Alimentacion"],
            $_POST["datos_paciente"]["Diagnostico_Social"],
            $_POST["datos_paciente"]["Bienes_Servicios"]
        );
            $message = [
                        'status' => true,
                        'message' => "Exito",
                        'data'=>$datos

                    ];
		$this->response($message, REST_Controller::HTTP_OK);
    }

    
}