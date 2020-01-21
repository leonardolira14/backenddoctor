<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'api/users/login';

$route['savedatap']= 'paciente/savedatag';
$route['updatedatap']= 'paciente/updatedatap';
$route['updatefotopaciente']= 'paciente/changefoto';
$route['getlispacientes']= 'paciente/getall';
$route['getdatapacientes']= 'paciente/getdata';
$route['updatestatus']='paciente/updatestatus';
$route['busquedapaciente']='paciente/busqueda';

$route['savedatadoctor']='doctor/savedata';
$route['updatefotodoctor']= 'doctor/changefoto';
$route['getlisdoctor']= 'doctor/getall';
$route['bajadoctor']="doctor/bajaDoctor";
$route['getdatadoctor']="doctor/getdata";
$route['updatedatadoctor']="doctor/update";
$route['busquedadoctor']="doctor/busqueda";

$route['saveunidadmedica']="UnidadMedica/save";
$route['updateunidadmedica']="UnidadMedica/update";
$route['getdataunidadmedica']="UnidadMedica/getData";
$route['getallunidadmedica']="UnidadMedica/getall";
$route['searchunidadmedica']="UnidadMedica/busqueda";
$route['bajaunidad']="UnidadMedica/bajaunidad";


$route['saveadministrativo']="administrativo/save";
$route['updateadministrativo']="administrativo/update";
$route['getdataadministrativo']="administrativo/getData";
$route['getalladministrativo']="administrativo/getDataAll";
$route['searchadministrativo']="administrativo/busqueda";
$route['updatefotoadministrativo']= 'administrativo/changefoto';
$route['bajaadministrativo']="administrativo/bajaa";

$route['pruebatoke'] = 'api/users/prueba';