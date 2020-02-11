<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Citas extends CI_Model
{
    protected $unidad_table = 'citas';
    function __construct()
	{
        $this->load->database();
        $this->load->model("Model_Doctor");
         
    }
    public function save($IDPaciente,$Nombre_Paciente,$IDDoctor,$Fecha,$Hora,$Comentarios){
        $array=array("Nombre_Paciente"=>$Nombre_Paciente,"Paciente_ID"=>$IDPaciente,"IDDoctor"=>$IDDoctor,"Fecha"=>$Fecha,"Hora"=>$Hora,"Comentarios"=>$Comentarios);
       return  $this->db->insert($this->unidad_table,$array);
    }
    public function getdatecitas($inicio,$fin){
        $respuesta=$this->db->where( "DATE(Fecha) BETWEEN '$inicio' AND '$fin'", NULL, FALSE )->get($this->unidad_table);
        
        $citas=$respuesta->result_array();
        foreach($citas as $index=>$cita){
            $datos_doctor=$this->Model_Doctor->getdata($cita["IDDoctor"]);
            $citas[$index]["Doctor"]=$datos_doctor["Nombre"]." ".$datos_doctor["Apellido_Mat"]." ".$datos_doctor["Apellido_Pat"];
            
        }
        return $citas;
    }
}