<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Unidad extends CI_Model
{
    protected $unidad_table = 'unidad_medica';


    public function getdata($_IDUnidad){
        $respuesta=$this->db->select('*')->where("IDUnidad='$_IDUnidad'")->get($this->unidad_table);
        if( $respuesta->num_rows() ){
            return $respuesta->row_array();
         }else{
              return FALSE;
         }
    }

    public function updatestatus($_IDUnidad,$_Status){
        $array=array("Status"=>$_Status);
        return $this->db->where("IDUnidad='$_IDUnidad'")->update($this->unidad_table,$array);
        
    }

     // funcion para obtner todas las unidades medicas
    public function getall(){
        $respuesta=$this->db->select('*')->get($this->unidad_table);
         if( $respuesta->num_rows() ){
            return $respuesta->result_array();
         }else{
              return FALSE;
         }
    }



    public function save(
        $_Nombre,
        $_Razon_Social,
        $_Adminstrativo_Responsable,
        $_Doctor_Responsable,
        $_Personal_Contacto,
        $_Email,
        $_Telefono1,
        $_Telefono2,
        $_Estado,
        $_Municipio,
        $_CP,
        $_CalleNumero,
        $_Ciudad
    ){
        $array=array(
            "Nombre"=>$_Nombre,
            "Razon_Social"=>$_Razon_Social,
            "Administrativo_Responsable"=>$_Adminstrativo_Responsable,
            "Doctor_Responsable"=>$_Doctor_Responsable,
            "Personal_Contacto"=>$_Personal_Contacto,
            "Email"=>$_Email,
            "Telefono1"=>$_Telefono1,
            "Telefono2"=>$_Telefono2,
            "Estado"=>$_Estado,
            "Municipio"=>$_Municipio,
            "CodigoPostal"=>$_CP,
            "CalleNumero"=>$_CalleNumero,
            "Ciudad"=>$_Ciudad
        );
        $this->db->insert($this->unidad_table,$array);
        $ultimoId = $this->db->insert_id();
        return  $ultimoId;

    }
    public function update(
        $_IDUnidad,
        $_Nombre,
        $_Razon_Social,
        $_Adminstrativo_Responsable,
        $_Doctor_Responsable,
        $_Personal_Contacto,
        $_Email,
        $_Telefono1,
        $_Telefono2,
        $_Estado,
        $_Municipio,
        $_CP,
        $_CalleNumero,
        $_Ciudad
    ){
        $array=array(
            "Nombre"=>$_Nombre,
            "Razon_Social"=>$_Razon_Social,
            "Administrativo_Responsable"=>$_Adminstrativo_Responsable,
            "Doctor_Responsable"=>$_Doctor_Responsable,
            "Personal_Contacto"=>$_Personal_Contacto,
            "Email"=>$_Email,
            "Telefono1"=>$_Telefono1,
            "Telefono2"=>$_Telefono2,
            "Estado"=>$_Estado,
            "Municipio"=>$_Municipio,
            "CodigoPostal"=>$_CP,
            "CalleNumero"=>$_CalleNumero,
            "Ciudad"=>$_Ciudad
        );
       return $this->db->where("IDUnidad='$_IDUnidad'")->update($this->unidad_table,$array);
       

    }

    // busqueda 
    public function busqueda($_Palabra){

       	//primero por nombre
		$_ResultadosN=$this->db->query("SELECT IDUnidad FROM  $this->unidad_table WHERE Nombre LIKE '%$_Palabra%'");
        $_ResultadosN=$_ResultadosN->result_array();
        
        // por apellido paterno
		$_ResultadosRS=$this->db->query("SELECT IDUnidad  FROM  $this->unidad_table WHERE Razon_Social LIKE '%$_Palabra%'");
        $_ResultadosRS=$_ResultadosRS->result_array();
        
       
       

        $todos=array_merge($_ResultadosN, $_ResultadosRS);
		
        $_Resultados=[];
        
        //ahora elimino los repetidos
		foreach($todos as $empresa){
			$bandera=false;
			$resulta=in_array_r($empresa["IDUnidad"],$_Resultados);
			if($resulta===false){
				array_push($_Resultados,array("IDUnidad"=>$empresa["IDUnidad"]));
			}			
		}
       $_Datos=[];
        foreach($_Resultados as $_Unidad){
		    $_DatosUnidad=$this->getdata($_Unidad["IDUnidad"]);
            array_push($_Datos,array(
                "IDUnidad"=>$_DatosUnidad["IDUnidad"],
                "Nombre"=>$_DatosUnidad["Nombre"],
                "Razon_Social"=>$_DatosUnidad["Razon_Social"],
                "Personal_Contacto"=>$_DatosUnidad["Personal_Contacto"],
                "Telefono1"=>$_DatosUnidad["Telefono1"]
            ));			
        }
        return $_Datos;
    }
}