<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
 
class Users extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        // Load User Model
        $this->load->model('Model_User','User_Model');
        $this->load->model('Doctor_Model');
        $this->load->library('Authorization_Token');
    }

    /**
     * User Register
     * --------------------------
     * @param: fullname
     * @param: username
     * @param: email
     * @param: password
     * --------------------------
     * @method : POST
     * @link : api/user/register
     */
    public function register_post()
    {
        header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);
        
        # Form Validation
        $this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]|alpha_numeric|max_length[20]',
            array('is_unique' => 'This %s already exists please enter another username')
        );
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[80]|is_unique[users.email]',
            array('is_unique' => 'This %s already exists please enter another email address')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            $insert_data = [
                'full_name' => $this->input->post('fullname', TRUE),
                'email' => $this->input->post('email', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => md5($this->input->post('password', TRUE)),
                'created_at' => time(),
                'updated_at' => time(),
            ];

            // Insert User in Database
            $output = $this->User_Model->insert_user($insert_data);
            if ($output > 0 AND !empty($output))
            {
                // Success 200 Code Send
                $message = [
                    'status' => true,
                    'message' => "User registration successful"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else
            {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Not Register Your Account."
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * User Login API
     * --------------------
     * @param: username or email
     * @param: password
     * --------------------------
     * @method : POST
     * @link: api/user/login
     */
    public function login_post()
    {
        header("Access-Control-Allow-Origin: *");
	    $_POST = json_decode(file_get_contents("php://input"), true);
        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);
        
        # Form Validation
        $this->form_validation->set_rules('username', 'Usuario', 'trim|required');
        $this->form_validation->set_rules('password', 'Contraseña', 'trim|required|max_length[100]');
        
			$array=array(
				"required"=>'El campo %s es obligatorio',
				"valid_email"=>'El campo %s no es valido',
				"min_length[3]"=>'El campo %s debe ser mayor a 3 Digitos',
				"min_length[10]"=>'El campo %s debe ser mayor a 10 Digitos',
				'alpha'=>'El campo %s debe estar compuesto solo por letras',
				"matches"=>"El campo %s no coinciden",
				'is_unique'=>'El contenido del campo %s ya esta registrado');
		$this->form_validation->set_message($array);
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            // Load Login Function
            $output = $this->User_Model->user_login($this->input->post('username'), $this->input->post('password'));
            
            if (!empty($output) AND $output != FALSE)
            {
                if($output->Tipo==='1'){
                    $data_user=$this->Doctor_Model->getdata($output->IDUsuario);  
                }
                
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');

                // Generate Token
                $token_data['ID_Usuario'] = $output->IDUsuario;
                $token_data['Tipo_Usuario'] = $output->Tipo;
                $token_data['Nombre'] = $data_user["Nombre"];
                $token_data['Usuario'] = $output->Usuario;
                $token_data['IDClinica'] = $data_user["IDClinica"];
                $token_data['time'] = time();

                $user_token = $this->authorization_token->generateToken($token_data);

                $data_user['token']=$user_token;
                $return_data = $data_user;
                // Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "Acceso consedido"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else
            {
                // Login Error
                $message = [
                    'status' => FALSE,
                    'message' => "Usuario y/o Contraseña no validos"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function prueba_get(){
        print_r($this->authorization_token->userData());
        exit();
    }
}