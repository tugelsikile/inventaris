<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller {
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
			$data['body'] = 'manage_brand';
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
			if (!$order){ $order = 'brand_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql = "SELECT	br.*,COUNT(ite.items_id) AS items_count
					FROM	tkj_items_brand AS br
					LEFT JOIN tkj_items AS ite ON br.brand_id = ite.brand_id
					WHERE	br.brand_name LIKE '%".$keyword."%' AND br.brand_status = 1
					GROUP BY br.brand_id
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT	br.brand_id
					FROM	tkj_items_brand AS br
					WHERE	br.brand_name LIKE '%".$keyword."%' AND br.brand_status = 1
					ORDER BY ".$order." ".$direction." ";
			$data_items	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_items){
				$json['msg'] = 'Tidak ada data Merek';
			} else {
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_items;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_brand_data',$data,TRUE);
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
			$this->load->view('form/brand_new');
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
			$data_nama	= $this->dbase->dataRow('items_brand',array('brand_name'=>$nama,'brand_status'=>1));
			
			if (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama Merek </strong>harus diisi';
				$json['elem'] = 'nama';
			} elseif ($data_nama){
				$json['msg'] = '<strong>Nama Merek </strong>sudah terdaftar';
				$json['elem'] = 'nama';
			} else {
				$cat_id = $this->dbase->dataInsert('items_brand',array('brand_name'=>$nama));
				if (!$cat_id){
					$json['msg'] = 'DB Error';
				} else {
					$data['data'] = $this->dbase->dataRow('items_brand',array('brand_id'=>$cat_id));
					$data['data']->items_count = 0;
					$json['html'] = $this->load->view('manage_brand_row',$data,TRUE);
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
			$data_items	= $this->dbase->dataRow('items_brand',array('brand_id'=>$items_id,'brand_status'=>1));
			if (!$data_items || !$items_id){
				die('Invalid data kategori');
			} else {
				$data['data'] = $data_items;
				$this->load->view('form/brand_edit',$data);
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
			$data_cat	= $this->dbase->dataRow('items_brand',array('brand_id'=>$cat_id,'brand_status'=>1));
			$data_nama	= $this->dbase->dataRow('items_brand',array('brand_name'=>$nama,'brand_status'=>1, 'brand_id !='=>$cat_id));
			
			if (!$cat_id || !$data_cat){
				$json['msg'] = 'Invalid data Merek';
			} elseif (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama Merek </strong>harus diisi';
				$json['elem'] = 'nama';
			} elseif ($data_nama){
				$json['msg'] = '<strong>Nama Merek </strong>sudah terdaftar';
				$json['elem'] = 'nama';
			} else {
				$this->dbase->dataUpdate('items_brand',array('brand_id'=>$cat_id),array('brand_name'=>$nama));
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
			$data_items	= $this->dbase->dataRow('items_brand',array('brand_id'=>$items_id,'brand_status'=>1),'brand_id');
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data Merek';
			} else {
				$this->dbase->dataUpdate('items_brand',array('brand_id'=>$items_id),array('brand_status'=>0));
				$json['t'] = 1;
				$json['msg'] = 'Merek berhasil dihapus';
			}
		}
		die(json_encode($json));
	}
}
