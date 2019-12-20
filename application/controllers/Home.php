<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require  APPPATH.'/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';
use Restserver\libraries\REST_Controller;
/**
 * 
 */
class Home extends REST_Controller {
	public function index_get()
	{
		$array = array("uno","dos","tres");
		$this->response($array);
	}
	public function vados_post()
	{
		$array = array("uno","dos","tres");
		$this->response($array);
	}
}
