
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Paciente extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        // Load User Model
      $this->load->model('doctor/doctor_model', 'DoctorModel');
        $this->load->model('paciente/paciente_model', 'PacienteModel');
        $this->load->library('Authorization_Token');
    }

    /**
     * get data paciente
     * ----------------------------------
     * @param: IDPaciente
     */
    public function getdata(){
        $respuesta=$this->where()->get();
    }
}