<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {
	function __construct(){
		parent::__construct();
	}
	function index(){
		if (!$this->session->userdata('inv_id')){
			$data['body'] = 'account/signin';
		} else {
			$this->load->library('acc');
			$user_id	= $this->session->userdata('inv_id');
			$data['peminjam'] = $this->dbase->dataResult('user',array('user_status'=>1,'user_level'=>0));
			$data['body'] = 'manage_user';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	public function signin(){
		if ($this->session->userdata('inv_id')){
			redirect(base_url());
		} else {
			$this->load->view('account/signin');
		}
	}
	function data_table(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('account'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$keyword	= $this->input->post('keyword');
			$page		= $this->input->post('page');
			if (!$page){ $page = 1; }
			$order		= $this->input->post('order');
			if (!$order){ $order = 'user_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql = "SELECT	us.*
					FROM	tkj_user AS us
					WHERE	us.user_name LIKE '%".$keyword."%' 
							AND us.user_status = 1 AND us.user_level > 0
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT	us.user_id
					FROM	tkj_user AS us
					WHERE	us.user_name LIKE '%".$keyword."%' 
							AND us.user_status = 1 AND us.user_level > 0
					ORDER BY ".$order." ".$direction." ";
			$data_items	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_items){
				$json['msg'] = 'Tidak ada data Peminjam';
			} else {
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_items;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_user_data',$data,TRUE);
			}
		}
		die(json_encode($json));
	}
	function new_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('account'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$this->load->view('form/account_new');
		}
	}
	function new_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('account'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$nama		= $this->input->post('nama');
			$data_nama	= $this->dbase->dataRow('user',array('user_name'=>$nama,'user_status'=>1));
			$password	= $this->input->post('password');
			$level		= $this->input->post('level');
			
			if (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama pengguna </strong>harus diisi';
				$json['elem'] = 'nama';
			} elseif ($data_nama){
				$json['msg'] = '<strong>Nama pengguna </strong>sudah terdaftar';
				$json['elem'] = 'nama';
			} elseif (strlen(trim($password)) == 0){
				$json['msg'] = '<strong>Password pengguna</strong> harus diisi';
				$json['elem'] = 'password';
			} elseif (strlen(trim($password)) < 6){
				$json['msg'] = '<strong>Password pengguna</strong> terlalu pendek';
				$json['elem'] = 'password';
			} else {
				$passwordHash = password_hash($password, PASSWORD_DEFAULT);
				$cat_id = $this->dbase->dataInsert('user',array('user_name'=>$nama,'user_password'=>$passwordHash,'user_password_raw'=>$password,'user_level'=>$level));
				if (!$cat_id){
					$json['msg'] = 'DB Error';
				} else {
					$this->load->library('conv');
					$data['data'] = $this->dbase->dataRow('user',array('user_id'=>$cat_id));
					$json['html'] = $this->load->view('manage_user_row',$data,TRUE);
					$json['t'] = 1;
				}
			}
		}
		die(json_encode($json));
	}
	function edit_data($items_id){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$data_items	= $this->dbase->dataRow('user',array('user_id'=>$items_id,'user_status'=>1));
			if (!$data_items || !$items_id){
				die('Invalid data Pengguna');
			} else {
				$data['data'] = $data_items;
				$this->load->view('form/account_edit',$data);
			}
		}
	}
	function edit_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$cat_id		= $this->input->post('cat_id');
			$nama		= $this->input->post('nama');
			$data_cat	= $this->dbase->dataRow('items_category',array('cat_id'=>$cat_id,'cat_status'=>1));
			$data_nama	= $this->dbase->dataRow('items_category',array('cat_name'=>$nama,'cat_status'=>1, 'cat_id !='=>$cat_id));
			
			if (!$cat_id || !$data_cat){
				$json['msg'] = 'Invalid data kategori';
			} elseif (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama kategori </strong>harus diisi';
				$json['elem'] = 'nama';
			} elseif ($data_nama){
				$json['msg'] = '<strong>Nama Kategori </strong>sudah terdaftar';
				$json['elem'] = 'nama';
			} else {
				$this->dbase->dataUpdate('items_category',array('cat_id'=>$cat_id),array('cat_name'=>$nama));
				$json['t'] = 1; $json['nama'] = $nama; $json['id'] = $cat_id;
			}
		}
		die(json_encode($json));
	}
	function signin_submit(){
		if ($this->session->userdata('inv_id')){
			$json['t'] = 0;
		} elseif (!$this->input->is_ajax_request()) {
			$json['t'] = 1;		$json['msg'] = 'Invalid request';
		} else {
			$json['t'] = 1;
			$username		= $this->input->post('username');
			$password		= $this->input->post('password');
			$data_user	= $this->dbase->dataRow('user',array('user_name'=>$username,'user_status'=>1));
			if (strlen(trim($username)) == 0){
				$json['msg'] = 'Masukkan username';	
				$json['class'] = 'username';
			} elseif (!$data_user){
				$json['msg'] = 'Username tidak terdaftar';
				$json['class'] = 'username';
			} elseif (!password_verify($password,$data_user->user_password)){
				$json['msg'] = 'Password invalid';
				$json['class'] = 'password';
			} else {
				$arr = array(
					'inv_id' => $data_user->user_id, 'inv_name' => $data_user->user_name, 'inv_level' => $data_user->user_level,
					'inv_fullname' => $data_user->user_fullname
				);
				$this->session->set_userdata($arr);
				$json['t'] = 2;
			}
		}
		die(json_encode($json));
	}
	function signout(){
		if (!$this->session->userdata('inv_id')){
			redirect(base_url('account/signin'));
		} else {
			$this->session->sess_destroy();
			redirect(base_url());
		}
	}
}
