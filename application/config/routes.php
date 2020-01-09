<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'api/users/login';

$route['savedatap']= 'paciente/savedatag';
$route['updatefotopaciente']= 'paciente/changefoto';
$route['getdatapacientes']= 'paciente/getall';

$route['pruebatoke'] = 'api/users/prueba';