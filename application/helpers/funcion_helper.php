<?

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists("generate_clave")){
	function generate_clave(){
	  $cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	  $cadena_base .= '0123456789' ;
	  $cadena_base .= '!@#%^&*()_,./<>?;:[]{}\|=+';
	 
	  $password = '';
	  $limite = strlen($cadena_base) - 1;
	 
	  for ($i=0; $i < 7; $i++)
	    $password .= $cadena_base[rand(0, $limite)];
	 
	  return $password;
	}
}