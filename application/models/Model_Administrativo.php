<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Administrativo extends CI_Model
{
    protected $unidad_table = 'personal_administrativo';


    //funcion para guardar los datos de paciente
    public function save_data(
        $_Nombre,
        $_Apellidos,
        $_Fecha_Nacimiento,
        $_Edad,
        $_Sexo,
        $_Curp,
        $_RFC,
        $_Telefono,
        $_Movil,
        $_Email,
        $_Unidad_Medica,
        $_Departamento,
        $_Funciones,
        $_Horario       
        ){
            $array=array(
                "Nombre"=>$_Nombre,
                "Apellidos"=>$_Apellidos,
                "Celular"=>$_Movil,
                "Telefono"=>$_Telefono,
                "RFC"=>$_RFC,
                "Correo"=>$_Email,
                "Curp"=>$_Curp,
                "Sexo"=>$_Sexo,
                "IDClinica"=>$_Unidad_Medica,
                "Departamento"=>$_Departamento,
                "Edad"=>$_Edad,
                "Funciones"=>$_Funciones,
                "Horario"=>$_Horario,
                "Fecha_Nacimiento"=>$_Fecha_Nacimiento
            );
            $this->db->insert($this->unidad_table,$array);
            $ultimoId = $this->db->insert_id();
            return  $ultimoId;
    }
    public function update_data(
        $_IDAdministrativo,
        $_Nombre,
        $_Apellidos,
        $_Fecha_Nacimiento,
        $_Edad,
        $_Sexo,
        $_Curp,
        $_RFC,
        $_Telefono,
        $_Movil,
        $_Email,
        $_Unidad_Medica,
        $_Departamento,
        $_Funciones,
        $_Horario       
        ){
            $array=array(
                "Nombre"=>$_Nombre,
                "Apellidos"=>$_Apellidos,
                "Celular"=>$_Movil,
                "Telefono"=>$_Telefono,
                "RFC"=>$_RFC,
                "Correo"=>$_Email,
                "Curp"=>$_Curp,
                "Sexo"=>$_Sexo,
                "IDClinica"=>$_Unidad_Medica,
                "Departamento"=>$_Departamento,
                "Edad"=>$_Edad,
                "Funciones"=>$_Funciones,
                "Horario"=>$_Horario,
                "Fecha_Nacimiento"=>$_Fecha_Nacimiento
            );
        return $this->db->where("IDAdministrativo='$_IDAdministrativo'")->update($this->unidad_table,$array);
           
    }
    public function getAll(){
        $q = $this->db->get($this->unidad_table);
       if( $q->num_rows() ) 
        {
         return $q->result_array();
        }else{
            return FALSE;
        }

    }
    public function  getdata($_ID){
        $this->db->where('IDAdministrativo', $_ID);
        $q = $this->db->get($this->unidad_table);
       if( $q->num_rows() ) 
        {
         return $q->row_array();
        }else{
            return FALSE;
        }
    }

     // funcion para actualizat la foto de un paciente
    public function update_foto($_ID_Administrativo,$_Foto){
        $array=array(
             "Foto"=>$_Foto,
        );
        return $this->db->where("IDAdministrativo='$_ID_Administrativo'")->update($this->unidad_table,$array);
    }

}