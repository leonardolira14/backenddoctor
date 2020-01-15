<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Paciente extends CI_Model
{
    protected $paciente_table = 'paciente';
    protected $datos_emergencia_table = 'emergencia_datos_paciente';
    protected $direcciones_table = 'direcciones_paciente';
    protected $discapacidad_table = 'discapacidad_paciente';

    //funcion para guardar los datos de paciente
    public function save_data(
        $_Nombre,
        $_Apellido_Materno,
        $_Apellido_Paterno,
        $_Fecha_Nacimineto,
        $_Edad,
        $_Sexo,
        $_Curp,
        $_Correo,
        $_NumExpedienteAnterior,
        $_Foto,
        $_Telefono,
        $_Movil,
        $_Discapacitado,
        $_Grupo_Sanguineo,
        $_Religion,
        $_Ocupacion
        ){
         $array=array(
             "Nombre"=>$_Nombre,
             "Apellido_Pat"=>$_Apellido_Paterno,
             "Apellido_Mat"=>$_Apellido_Materno,
             "Edad"=>$_Edad,
             "Fecha_Nacimiento"=>$_Fecha_Nacimineto,
             "Curp"=>$_Curp,
             "NumExpedienteAnterior"=>$_NumExpedienteAnterior,
             "Foto"=>$_Foto,
             "Sexo"=>$_Sexo,
             "Email"=>$_Correo,
             "Telefono"=>$_Telefono,
             "Movil"=>$_Movil,
             "Discapacitado"=>$_Discapacitado,
             "Grupo_Sanguineo"=>$_Grupo_Sanguineo,
             "Religion"=> $_Religion,
             "Ocupacion"=>$_Ocupacion
         );
         $this->db->insert($this->paciente_table,$array);
        $ultimoId = $this->db->insert_id();
        return  $ultimoId;
    }

    //funcion para guardar la direccion del paciente
    public function save_direccion(
        $_ID_Paciente,
        $_Calle_Y_Numero,
        $_Colonia,
        $_Municipio,
        $_Estado,
        $_CP
        ){
            $array=array(
             "IDPaciente"=>$_ID_Paciente,
             "Calle_Y_Numero"=>$_Calle_Y_Numero,
             "Colonia"=>$_Colonia,
             "Municipio"=>$_Municipio,
             "Estado"=>$_Estado,
             "CP"=>$_CP
         );
        $this->db->insert($this->direcciones_table,$array);
    }
     //funcion para guardar la persona para emergenmcias del paciente
    public function save_data_emergencias(
        $_ID_Paciente,
        $_Nombre,
        $_Apellidos,
        $_Parentesco,
        $_Correo,
        $_Telefono,
        $_Movil
        ){
        $array=array(
             "IDPaciente"=>$_ID_Paciente,
             "Nombre"=>$_Nombre,
             "Apellidos"=>$_Apellidos,
             "Parentesco"=>$_Parentesco,
             "Correo"=>$_Correo,
             "Telefono"=>$_Telefono,
             "Movil"=>$_Movil,
         );
        $this->db->insert($this->datos_emergencia_table,$array);
    }
    
    // funcion para actualizat la foto de un paciente
    public function update_foto($_ID_Paciente,$_Foto){
        $array=array(
             "Foto"=>$_Foto,
        );
        return $this->db->where("IDPaciente='$_ID_Paciente'")->update($this->paciente_table,$array);
    }

    // funcion para obtner todos los pacientes
    public function getall(){
        $respuesta=$this->db->select('*')->get($this->paciente_table);
         if( $respuesta->num_rows() ){
            return $respuesta->result_array();
         }else{
              return FALSE;
         }
    }

    // funcion para guardar la discapacidad de una persona
    public function savediscapacidad($_ID_Paciente,$_Nombre,$_Apellido,$_Parentesco,$_Causa){
        $array=array(
            "IDPaciente"=>$_ID_Paciente,
            "Nombre"=>$_Nombre,
            "Apellidos"=>$_Apellido,
            "Parentesco"=>$_Parentesco,
            "Causa"=>$_Causa
        );
        return $this->db->insert($this->discapacidad_table,$array);
    }

    // funcion para obter los datos de un paciente
    public function getdata_by_ID($_IDPaciente){
        $respuesta=$this->db->select('*')->where("IDPaciente='$_IDPaciente'")->get($this->paciente_table);
        
        return $respuesta->row_array();
    }

    //funcion para obtner la direccion de un paciente
    public function getdress_by_ID($_IDPaciente){
        $respuesta=$this->db->select('*')->where("IDPaciente='$_IDPaciente'")->get($this->direcciones_table);
        
        return $respuesta->row_array();
    }

    //funcion para obtner los datos de emergencia de un paciente
    public function getemergenci_by_ID($_IDPaciente){
        $respuesta=$this->db->select('*')->where("IDPaciente='$_IDPaciente'")->get($this->datos_emergencia_table);
        
        return $respuesta->row_array();
    }
    
    //funcion para obtner los datos de discapacidad de un paciente
    public function getdatadiscapacidad_by_ID($_IDPaciente){
        $respuesta=$this->db->select('*')->where("IDPaciente='$_IDPaciente'")->get($this->discapacidad_table);
        
        return $respuesta->row_array();
    }

    //funcion para actualizar los datos de un paciente
    public function update_data(
        $_ID_Paciente,
        $_Nombre,
        $_Apellido_Materno,
        $_Apellido_Paterno,
        $_Fecha_Nacimineto,
        $_Edad,
        $_Sexo,
        $_Curp,
        $_Correo,
        $_NumExpedienteAnterior,
        $_Telefono,
        $_Movil,
        $_Discapacitado,
        $_Grupo_Sanguineo,
        $_Religion,
        $_Ocupacion
        ){
         $array=array(
             "Nombre"=>$_Nombre,
             "Apellido_Pat"=>$_Apellido_Paterno,
             "Apellido_Mat"=>$_Apellido_Materno,
             "Edad"=>$_Edad,
             "Fecha_Nacimiento"=>$_Fecha_Nacimineto,
             "Curp"=>$_Curp,
             "NumExpedienteAnterior"=>$_NumExpedienteAnterior,
             "Sexo"=>$_Sexo,
             "Email"=>$_Correo,
             "Telefono"=>$_Telefono,
             "Movil"=>$_Movil,
             "Discapacitado"=>$_Discapacitado,
             "Grupo_Sanguineo"=>$_Grupo_Sanguineo,
             "Religion"=>$_Religion,
             "Ocupacion"=>$_Ocupacion
         );
         return $this->db->where("IDPaciente='$_ID_Paciente'")->update($this->paciente_table,$array);
       
    }
    //funcion para actualizar la direccion del paciente
    public function update_direccion(
        $_ID_Paciente,
        $_Calle_Y_Numero,
        $_Colonia,
        $_Municipio,
        $_Estado,
        $_CP
        ){
            $array=array(
             "Calle_Y_Numero"=>$_Calle_Y_Numero,
             "Colonia"=>$_Colonia,
             "Municipio"=>$_Municipio,
             "Estado"=>$_Estado,
             "CP"=>$_CP
         );
        $this->db->where("IDPaciente='$_ID_Paciente'")->update($this->direcciones_table,$array);
    }
     //funcion para actulizar la persona para emergenmcias del paciente
    public function update_data_emergencias(
        $_ID_Paciente,
        $_Nombre,
        $_Apellidos,
        $_Parentesco,
        $_Correo,
        $_Telefono,
        $_Movil
        ){
        $array=array(
             "Nombre"=>$_Nombre,
             "Apellidos"=>$_Apellidos,
             "Parentesco"=>$_Parentesco,
             "Correo"=>$_Correo,
             "Telefono"=>$_Telefono,
             "Movil"=>$_Movil,
         );
        $this->db->where("IDPaciente='$_ID_Paciente'")->update($this->datos_emergencia_table,$array);
    }

    // funcion para actulizar la discapacidad de una persona
    public function update_discapacidad($_ID_Paciente,$_Nombre,$_Apellido,$_Parentesco,$_Causa){
        $array=array(
            "IDPaciente"=>$_ID_Paciente,
            "Nombre"=>$_Nombre,
            "Apellidos"=>$_Apellido,
            "Parentesco"=>$_Parentesco,
            "Causa"=>$_Causa
        );
        return $this->db->insert($this->discapacidad_table,$array);
    }

    //funcion para cambiar el status de un paciente 
    public function update_status($_ID_Paciente,$_Status){
        $status=array("Status"=>$_Status);
        return $this->db->where("IDPaciente='$_ID_Paciente'")->update($this->paciente_table,$status);
    }

}