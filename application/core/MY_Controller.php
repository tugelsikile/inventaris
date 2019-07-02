<?php 
class MY_Controller extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		//$this->load->library('ion_auth');
		//check_installer();
	}
}
