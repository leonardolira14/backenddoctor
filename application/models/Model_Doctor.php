<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Doctor extends CI_Model
{
    protected $user_table = 'datos_doctor';

    /**
     * doctor get data
     * ----------------------------------
     * @param: iddoctor
     */
    public function getdata($_ID){
        $this->db->where('IDDatos_Doc', $_ID);
        $q = $this->db->get($this->user_table);
       if( $q->num_rows() ) 
        {
         return $q->row_array();
        }else{
            return FALSE;
        }
    }
}