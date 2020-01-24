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

$route['saveunidadmedica']="Unidadmedica/save";
$route['updateunidadmedica']="Unidadmedica/update";
$route['getdataunidadmedica']="Unidadmedica/getData";
$route['getallunidadmedica']="Unidadmedica/getall";
$route['searchunidadmedica']="Unidadmedica/busqueda";
$route['bajaunidad']="Unidadmedica/bajaunidad";


$route['saveadministrativo']="administrativo/savedata";
$route['updateadministrativo']="administrativo/update";
$route['getdataadministrativo']="administrativo/getData";
$route['getalladministrativo']="administrativo/getDataAll";
$route['searchadministrativo']="administrativo/busqueda";
$route['updatefotoadministrativo']= 'administrativo/changefoto';
$route['bajaadministrativo']="administrativo/baja";

$route['saveasistente']="asistentemedico/save";
$route['updateasistente']="asistentemedico/update";
$route['getdataasistente']="asistentemedico/getdata";
$route['getallasistente']="asistentemedico/getall";
$route['searchasistente']="asistentemedico/buscar";
$route['updatefotoasistente']= 'asistentemedico/changefoto';
$route['bajaasistente']="asistentemedico/baja";

$route['pruebatoke'] = 'api/users/prueba';