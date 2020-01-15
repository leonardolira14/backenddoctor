<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Doctor extends CI_Model
{
    protected $doctor_table = 'datos_doctor';

    function __construct()
	{
        $this->load->database();
         
    }
    
    // funcion para obtner todos los doctores
    public function getall(){
        $respuesta=$this->db->select('*')->get($this->doctor_table);
         if( $respuesta->num_rows() ){
            return $respuesta->result_array();
         }else{
              return FALSE;
         }
    }


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

    /**
     * save doctor data 
     * ----------------------------------
     * @param: iddoctor
     */
    public function save_data(
        $_Nombre,
        $_ApellidoPaterno,
        $_ApellidoMaterno,
        $_Fecha_Nacimiento,
        $_Edad,
        $_Sexo,
        $_Curp,
        $_RFC,
        $_Telefono,
        $_Movil,
        $_Email,
        $_Unidad_Medica,
        $_Especialidad,
        $_Cedula, 
        $_Institucion,
        $_Especialidades,
        $_Dias_Consulta
        ){
            $array=array(
                "Nombre"=>$_Nombre,
                "Apellido_Pat"=>$_ApellidoPaterno,
                "Apellido_Mat"=>$_ApellidoMaterno,
                "Celular"=>$_Movil,
                "Telefono"=>$_Telefono,
                "RFC"=>$_RFC,
                "Correo"=>$_Email,
                "Curp"=>$_Curp,
                "Sexo"=>$_Sexo,
                "IDClinica"=>$_Unidad_Medica,
                "Especialidad"=>$_Especialidad,
                "Cedula_General"=>$_Cedula,
                "Institucion_Cedula"=>$_Institucion,
                "Especialidades"=> $_Especialidades,
                "Informacion_Consultas"=>$_Dias_Consulta
            );
            $this->db->insert($this->doctor_table,$array);
            $ultimoId = $this->db->insert_id();
            return  $ultimoId;
    }
    // funcion para actualizat la foto de un paciente
    public function update_foto($_ID_Doctor,$_Foto){
        $array=array(
             "Foto"=>$_Foto,
        );
        return $this->db->where("IDDatos_Doc='$_ID_Doctor'")->update($this->doctor_table,$array);
    }
}