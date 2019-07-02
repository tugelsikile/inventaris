<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {
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
			$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
			$data['peminjam'] = $this->dbase->dataResult('user',array('user_status'=>1,'user_level'=>0));
			$data['body'] = 'manage_category';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function data_table(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$keyword	= $this->input->post('keyword');
			$page		= $this->input->post('page');
			if (!$page){ $page = 1; }
			$order		= $this->input->post('order');
			if (!$order){ $order = 'cat_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql = "SELECT	ca.*,COUNT(ite.items_id) AS items_count
					FROM	tkj_items_category AS ca
					LEFT JOIN tkj_items AS ite ON ite.cat_id = ca.cat_id
					WHERE	ca.cat_name LIKE '%".$keyword."%' AND ca.cat_status = 1 
					GROUP BY ca.cat_id
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT ca.cat_id
					FROM	tkj_items_category AS ca
					WHERE	ca.cat_name LIKE '%".$keyword."%' AND ca.cat_status = 1
					GROUP BY ca.cat_id
					ORDER BY ".$order." ".$direction." ";
			$data_items	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_items){
				$json['msg'] = 'Tidak ada data kategori';
			} else {
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_items;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_category_data',$data,TRUE);
			}
		}
		die(json_encode($json));
	}
	function new_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$this->load->view('form/category_new');
		}
	}
	function new_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$nama		= $this->input->post('nama');
			$data_nama	= $this->dbase->dataRow('items_category',array('cat_name'=>$nama,'cat_status'=>1));
			
			if (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama kategori </strong>harus diisi';
				$json['elem'] = 'nama';
			} elseif ($data_nama){
				$json['msg'] = '<strong>Nama Kategori </strong>sudah terdaftar';
				$json['elem'] = 'nama';
			} else {
				$cat_id = $this->dbase->dataInsert('items_category',array('cat_name'=>$nama));
				if (!$cat_id){
					$json['msg'] = 'DB Error';
				} else {
					$data['data'] = $this->dbase->dataRow('items_category',array('cat_id'=>$cat_id));
					$data['data']->items_count = 0;
					$json['html'] = $this->load->view('manage_category_row',$data,TRUE);
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
			$data_items	= $this->dbase->dataRow('items_category',array('cat_id'=>$items_id,'cat_status'=>1));
			if (!$data_items || !$items_id){
				die('Invalid data kategori');
			} else {
				$data['data'] = $data_items;
				$this->load->view('form/category_edit',$data);
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
	function delete_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('category'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$items_id	= $this->input->post('id');
			$data_items	= $this->dbase->dataRow('items_category',array('cat_id'=>$items_id,'cat_status'=>1),'cat_id');
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data kategori';
			} else {
				$this->dbase->dataUpdate('items_category',array('cat_id'=>$items_id),array('cat_status'=>0));
				$json['t'] = 1;
				$json['msg'] = 'Kategori berhasil dihapus';
			}
		}
		die(json_encode($json));
	}
}
