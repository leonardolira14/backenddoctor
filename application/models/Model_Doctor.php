<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Doctor extends CI_Model
{
    protected $doctor_table = 'datos_doctor';

    function __construct()
	{
        $this->load->database();
        $this->load->model('Model_Unidad');
         
    }
    
    // funcion para obtner todos los doctores
    public function getall(){
        $respuesta=$this->db->select('*')->get($this->doctor_table);
         if( $respuesta->num_rows() ){
                $lista_unidades=$respuesta->result_array();
                foreach ($lista_unidades as $clave => $valor){
                    $datos_unidad=$this->Model_Unidad->getdata($valor["IDClinica"]);
                    $lista_unidades[$clave]["Unidad"]=$datos_unidad["Nombre"];
                }
           return $lista_unidades;
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
        $q = $this->db->get($this->doctor_table);
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
        $_Dias_Consulta,
        $_Tipo_Doctor,
        $_Guardias
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
                "Informacion_Consultas"=>$_Dias_Consulta,
                "Tipo_Medico"=>$_Tipo_Doctor,
                "Guardias"=>$_Guardias,
                "Edad"=>$_Edad,
                "Fecha_Nacimiento"=>$_Fecha_Nacimiento
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

    public function baja_Doctor($_ID_Doctor,$_Status){
    $array=array(
        "Status"=>$_Status,
    );
    return $this->db->where("IDDatos_Doc='$_ID_Doctor'")->update($this->doctor_table,$array);
    }

    // funcion para actualizar datos 
    public function update_data(
        $_ID_Doctor,
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
        $_Dias_Consulta,
        $_Tipo_Doctor,
        $_Guardias
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
                "Informacion_Consultas"=>$_Dias_Consulta,
                "Tipo_Medico"=>$_Tipo_Doctor,
                "Guardias"=>$_Guardias,
                "Edad"=>$_Edad,
                "Fecha_Nacimiento"=>$_Fecha_Nacimiento
            );
            
           return  $this->db->where("IDDatos_Doc='$_ID_Doctor'")->update($this->doctor_table,$array);
    }

    // busqueda 
    public function busqueda($_Palabra){

       	//primero por nombre
		$_ResultadosN=$this->db->query("SELECT IDDatos_Doc FROM  datos_doctor WHERE Nombre LIKE '%$_Palabra%'");
        $_ResultadosN=$_ResultadosN->result_array();
        
        // por apellido paterno
		$_ResultadosAP=$this->db->query("SELECT IDDatos_Doc  FROM  datos_doctor WHERE Apellido_Pat LIKE '%$_Palabra%'");
        $_ResultadosAP=$_ResultadosAP->result_array();
        
        // por  apellido materno
		$_ResultadosAM=$this->db->query("SELECT IDDatos_Doc  FROM  datos_doctor WHERE Apellido_Mat LIKE '%$_Palabra%'");
        $_ResultadosAM=$_ResultadosAM->result_array();
        
        // por especialidad
		$_ResultadosE=$this->db->query("SELECT IDDatos_Doc  FROM  datos_doctor WHERE Especialidad LIKE '%$_Palabra%'");
        $_ResultadosE=$_ResultadosE->result_array();
        
         // por numero Cedula
		$_ResultadosNC=$this->db->query("SELECT IDDatos_Doc  FROM  datos_doctor WHERE Cedula_General LIKE '%$_Palabra%'");
		$_ResultadosNC=$_ResultadosNC->result_array();

        $todos=array_merge($_ResultadosN, $_ResultadosAP,$_ResultadosAM,$_ResultadosE,$_ResultadosNC);
		
        $_Resultados=[];
        
        //ahora elimino los repetidos
		foreach($todos as $empresa){
			$bandera=false;
			$resulta=in_array_r($empresa["IDDatos_Doc"],$_Resultados);
			if($resulta===false){
				array_push($_Resultados,array("IDDatos_Doc"=>$empresa["IDDatos_Doc"]));
			}			
		}
       $_Datos=[];
        foreach($_Resultados as $_Doctor){
		    $_DatosDoctor=$this->getdata($_Doctor["IDDatos_Doc"]);
            array_push($_Datos,array(
                "IDDatos_Doc"=>$_DatosDoctor["IDDatos_Doc"],
                "Nombre"=>$_DatosDoctor["Nombre"],
                "Apellido_Pat"=>$_DatosDoctor["Apellido_Pat"],
                "Apellido_Mat"=>$_DatosDoctor["Apellido_Mat"],
                "Celular"=>$_DatosDoctor["Celular"],
                "Especialidad"=>$_DatosDoctor["Especialidad"],
                "IDClinica"=>$_DatosDoctor["IDClinica"],
                "Foto"=>$_DatosDoctor["Foto"]
            ));			
        }
        return $_Datos;
    }
}