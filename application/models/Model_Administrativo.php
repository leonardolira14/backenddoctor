<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Administrativo extends CI_Model
{
    protected $unidad_table = 'personal_administrativo';
    function __construct()
	{
        $this->load->database();
        $this->load->model('Model_Unidad');
         
    }

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
                "Movil"=>$_Movil,
                "Telefono"=>$_Telefono,
                "RFC"=>$_RFC,
                "Email"=>$_Email,
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
                "Movil"=>$_Movil,
                "Telefono"=>$_Telefono,
                "RFC"=>$_RFC,
                "Email"=>$_Email,
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
            $resultados=$q->result_array();
            foreach($resultados as $key=>$value){
                 
                 if($value["IDClinica"]!==0){
                     $_DatosUnidad= $this->Model_Unidad->getdata($value["IDClinica"]);
                     $resultados[$key]["Unidad"]=$_DatosUnidad["Nombre"];
                 }
            }
         return $resultados;
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
    public function update_status($_ID,$_Status){
        $array=array("Status"=>$_Status);
            $this->db->where('IDAdministrativo', $_ID);
            $q = $this->db->update($this->unidad_table,$array);
             return $q;
    }

    // busqueda 
    public function busqueda($_Palabra){

       	//primero por nombre
		$_ResultadosN=$this->db->query("SELECT IDAdministrativo FROM  personal_administrativo WHERE Nombre LIKE '%$_Palabra%'");
        $_ResultadosN=$_ResultadosN->result_array();
        
        // por apellido paterno
		$_ResultadosAP=$this->db->query("SELECT IDAdministrativo  FROM  personal_administrativo WHERE Apellidos LIKE '%$_Palabra%'");
        $_ResultadosAP=$_ResultadosAP->result_array();
        
        // por  apellido materno
		$_ResultadosD=$this->db->query("SELECT IDAdministrativo  FROM  personal_administrativo WHERE Departamento LIKE '%$_Palabra%'");
        $_ResultadosD=$_ResultadosD->result_array();
        
        

        $todos=array_merge($_ResultadosN, $_ResultadosAP,$_ResultadosD);
		
        $_Resultados=[];
        
        //ahora elimino los repetidos
		foreach($todos as $empresa){
			$bandera=false;
			$resulta=in_array_r($empresa["IDAdministrativo"],$_Resultados);
			if($resulta===false){
				array_push($_Resultados,array("IDAdministrativo"=>$empresa["IDAdministrativo"]));
			}			
		}
       $_Datos=[];
        foreach($_Resultados as $_Doctor){
            $_DatosAdmin=$this->getdata($_Doctor["IDAdministrativo"]);
            $_DatosUnidad= $this->Model_Unidad->getdata($_DatosAdmin["IDClinica"]);
            array_push($_Datos,array(
                "IDAdministrativo"=>$_DatosAdmin["IDAdministrativo"],
                "Nombre"=>$_DatosAdmin["Nombre"],
                "Apellidos"=>$_DatosAdmin["Apellidos"],
                "Unidad"=>$_DatosAdmin["Nombre"],
                "Movil"=>$_DatosAdmin["Movil"],
                "Departamento"=>$_DatosAdmin["Departamento"],
                "Status"=>$_DatosAdmin["Status"],
                "Foto"=>$_DatosAdmin["Foto"]
            ));			
        }
        return $_Datos;
    }

}