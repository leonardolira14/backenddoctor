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


$route['savedatadoctor']='doctor/savedata';
$route['updatefotodoctor']= 'doctor/changefoto';
$route['getlisdoctor']= 'doctor/getall';

$route['pruebatoke'] = 'api/users/prueba';