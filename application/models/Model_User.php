<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_User extends CI_Model
{
    protected $user_table = 'usuarios';
    protected $cadena_add = 'HKJGKBGhjjfsf*-/jd_jdsf44';
    /**
     * Use Registration
     * @param: {array} User Data
     */
    public function insert_user(array $data) {
        $this->db->insert($this->user_table, $data);
        return $this->db->insert_id();
    }

    /**
     * User Login
     * ----------------------------------
     * @param: username or email address
     * @param: password
     */
    public function user_login($username, $password)
    {
        $this->db->where('Usuario', $username);
        $q = $this->db->get($this->user_table);

        if( $q->num_rows() ) 
        {
            $user_pass = $q->row('Clave');
            if(md5($password.$this->cadena_add)."-".$this->cadena_add === $user_pass) {
                return $q->row();
            }
            return FALSE;
        }else{
            return FALSE;
        }
    }
    /**
     * add User Login
     * ----------------------------------
     * @param: username or email address
     * @param: password
     * @param: Tipo
     * @param: IDPersona
     */
    public function add_user_login($username, $password,$_Tipo,$_ID)
    {
        $array=array(
            "Usuario"=>$username,
            "Clave"=>md5($password.$this->cadena_add)."-".$this->cadena_add,
            "Tipo"=>$_Tipo,
            "IDPersona"=>$_ID
        );
        $this->db->insert('Usuario', $array);
    }
  
}