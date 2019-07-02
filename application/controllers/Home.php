<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	function __construct(){
		parent::__construct();
		if (!$this->session->userdata('inv_id')){
			redirect(base_url('account/signin'));
		}
	}
	public function index(){
		if (!$this->session->userdata('inv_id')){
			$data['body'] = 'account/signin';
		} else {
			$this->load->library('acc');
			$user_id	= $this->session->userdata('inv_id');
			$data['peminjam'] = $this->dbase->dataResult('user',array('user_status'=>1,'user_level'=>0));
			$data['body'] = 'manage_pinjam';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
}
