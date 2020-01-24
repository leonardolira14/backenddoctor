<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Asistentemedico extends CI_Model
{
    protected $table = 'asistente_medico';
     public $all=false;
    public $ID='';

    function __construct()
	{
        $this->load->database();
        $this->load->model('Model_Unidad');
        
         
    }
    public function getdata($all=false,$_ID=''){
        if($all){
            $this->db->where('IDAsistente', $_ID);
        }
        $array=[];
        $q =$this->db->get($this->table);
        if( $q->num_rows() ) 
            {
            if($all){
                 return $q->row_array();
             }else{
                 $array=$q->result_array();
                 foreach($array as $key=>$valor){
                    $_Datos_Unidad=$this->Model_Unidad->getdata($valor["IDUnidad"]);
                    $array[$key]["Unidad"]=$_Datos_Unidad["Nombre"];
                 }
                return $array;
             }
            }else{
                return FALSE;
            }
    }
    public function update_foto($_ID,$_Foto){
      $array=array(
          "Foto"=>$_Foto
      );
     
     return $this->db->where("IDAsistente='$_ID'")->update($this->table,$array);
       
    }
    public function save( 
        $_Nombre,
        $_Apellidos,
        $_Fecha_Nacimiento,
        $_Sexo,
        $_Curp,
        $_RFC,
        $_Telefono,
        $_Movil,
        $_Email,
        $_Unidad_Medica,
        $_Horario,
        $_Comentarios,
        $_Departamento
        ){
        $array=array(
          "Nombre"=>$_Nombre,
          "Apellidos"=>$_Apellidos,
          "FechaNacimiento"=>$_Fecha_Nacimiento,
          "Sexo"=>$_Sexo,
          "Curp"=>$_Curp,
          "RFC"=>$_RFC,
          "Telefono"=>$_Telefono,
          "Movil"=>$_Movil,
          "Email"=>$_Email,
          "IDUnidad"=>$_Unidad_Medica,
          "Horario"=>$_Horario,
          "Departamento"=>$_Departamento,
          "Comentarios"=>$_Comentarios  
      );
   
        $this->db->insert($this->table,$array);
        $ultimoId = $this->db->insert_id();
        return  $ultimoId;

    }
    public function update(
        $_ID,
        $_Nombre,
        $_Apellidos,
        $_Fecha_Nacimiento,
        $_Sexo,
        $_Curp,
        $_RFC,
        $_Telefono,
        $_Movil,
        $_Email,
        $_Unidad_Medica,
        $_Horario,
        $_Comentarios,
        $_Departamento){
        
        $array=array(
          "Nombre"=>$_Nombre,
          "Apellidos"=>$_Apellidos,
          "FechaNacimiento"=>$_Fecha_Nacimiento,
          "Sexo"=>$_Sexo,
          "Curp"=>$_Curp,
          "RFC"=>$_RFC,
          "Telefono"=>$_Telefono,
          "Movil"=>$_Movil,
          "Email"=>$_Email,
          "IDUnidad"=>$_Unidad_Medica,
          "Horario"=>$_Horario,
          "Departamento"=>$_Departamento,
          "Comentarios"=>$_Comentarios  
      );
      return $this->db->where("IDAsistente='$_ID'")->update($this->table,$array);

    }
    public function update_status($_ID,$_Status){
        $array=array(
          "Status"=>$_Status  
      );
      return $this->db->where("IDAsistente='$_ID'")->update($this->table,$array);
    }
    
    public function busqueda($_Palabra){
        //primero por nombre
		$_ResultadosN=$this->db->query("SELECT IDAsistente FROM  asistente_medico WHERE Nombre LIKE '%$_Palabra%'");
        $_ResultadosN=$_ResultadosN->result_array();
        
        // por apellido paterno
		$_ResultadosAP=$this->db->query("SELECT IDAsistente  FROM  asistente_medico WHERE Apellidos LIKE '%$_Palabra%'");
        $_ResultadosAP=$_ResultadosAP->result_array();
        
        $todos=array_merge($_ResultadosN, $_ResultadosAP);
		
        $_Resultados=[];

        //ahora elimino los repetidos
		foreach($todos as $empresa){
			$bandera=false;
			$resulta=in_array_r($empresa["IDAsistente"],$_Resultados);
			if($resulta===false){
				array_push($_Resultados,array("IDAsistente"=>$empresa["IDAsistente"]));
			}			
        }
        
        $_Datos=[];
        foreach($_Resultados as $_Doctor){
            $_DatosAsistente=$this->get(True,$_Doctor["IDAsistente"]);
            $_Datos_Unidad=$this->Model_Unidad->getdata($_DatosAsistente["IDClinica"]);
            array_push($_Datos,array(
                "IDAsistente"=>$_DatosAsistente["IDAsistente"],
                "Nombre"=>$_DatosAsistente["Nombre"],
                "Apellidos"=>$_DatosAsistente["Apellidos"],
                "Departamento"=>$_DatosAsistente["Departamento"],
                "Celular"=>$_DatosAsistente["Celular"],
                "IDClinica"=>$_Datos_Unidad["Nombre"],
                "Foto"=>$_DatosAsistente["Foto"],
                "Status"=>$_DatosAsistente["Status"]
            ));			
        }
        return $_Datos;
    }
    
}