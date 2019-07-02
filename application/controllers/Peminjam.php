<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjam extends MY_Controller {
	function __construct(){
		parent::__construct();
	}
	function index(){
		if (!$this->session->userdata('inv_id')){
			$data['body'] = 'account/signin';
		} else {
			$this->load->library('acc');
			$user_id	= $this->session->userdata('inv_id');
			$data['body'] = 'manage_peminjam';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
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
							AND us.user_status = 1 AND us.user_level = 0
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT	us.user_id
					FROM	tkj_user AS us
					WHERE	us.user_name LIKE '%".$keyword."%' 
							AND us.user_status = 1 AND us.user_level = 0
					ORDER BY ".$order." ".$direction." ";
			$data_peminjam	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_peminjam){
				$json['msg'] = 'Tidak ada data Peminjam';
			} else {
				$i = 0;
				while(list(,$val) = each($data_peminjam)){
					$data_peminjam[$i] = $val;
					//$sql = "";
					//$data_peminjam[$i]->pinjam = $this->dbase->sqlResult($sql);
					$i++;
				}
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_peminjam;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_peminjam_data',$data,TRUE);
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
			$this->load->view('form/peminjam_new');
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
			if (strlen(trim($nama)) == 0){
				$json['msg'] = '<strong>Nama Peminjam </strong>harus diisi';
				$json['elem'] = 'nama';
			} else {
				$username 	= url_title(substr($nama,0,50), '', TRUE);
				$data_nama	= $this->dbase->dataResult('user',array('user_name'=>$username),'user_id');
				if ($data_nama){
					$this->load->helper('string');
					$username = increment_string($username, '', count($data_nama)+1);
				}
				$passwordHash = password_hash($nama, PASSWORD_DEFAULT);
				$cat_id = $this->dbase->dataInsert('user',array('user_name'=>$username,'user_fullname'=>$nama,'user_level'=>0,'user_password_raw'=>$nama,'user_password'=>$passwordHash));
				if (!$cat_id){
					$json['msg'] = 'DB Error';
				} else {
					$this->load->library('conv');
					$data['data'] = $this->dbase->dataRow('user',array('user_id'=>$cat_id));
					$data['data']->pinjaman = array();
					$html = '';
					$data_peminjam = $this->dbase->dataResult('user',array('user_status'=>1,'user_level'=>0));
					if ($data_peminjam){
						foreach($data_peminjam as $val){
							$html .= '<option value="'.$val->user_id.'">'.$val->user_fullname.'</option>';
						}
					}
					$json['peminjam'] = $html;
					$json['html'] = $this->load->view('manage_peminjam_row',$data,TRUE);
					$json['t'] = 1;
				}
			}
		}
		die(json_encode($json));
	}
	function add_pinjam(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('account'));
		} else {
			$pin_userid	= $this->input->post('pin_userid');
			$data_pinus	= $this->dbase->dataRow('user',array('user_id'=>$pin_userid));
			$user_id	= $this->session->userdata('inv_id');
			$data_user	= $this->dbase->dataRow('user',array('user_id'=>$user_id));
			$items_id	= $this->input->post('items_id');
			$items_qty	= $this->input->post('items_qty');
			$items_flag	= $this->input->post('items_flag');
			$json['t']	= 0;
			if (!$data_pinus || !$pin_userid){
				$json['msg'] = 'Pilih siapa yang ingin meminjam';
			} elseif (!$user_id || !$data_user){
				$json['msg'] = 'Hanya admin yang bisa membolehkan orang meminjam';
			} elseif (count($items_id) == 0 || count($items_qty) == 0){
				$json['msg'] = 'Pilih dulu barang dan banyak barang yang akan dipinjam';
			} else {
				$pin_code = "SELECT MAX(pin_code) AS pin_code FROM tkj_items_pinjam WHERE DATE(pin_date) = DATE(NOW())";
				$pin_code = $this->dbase->sqlRow($pin_code)->pin_code;
				if (!$pin_code){
					$pin_code = date('Ymd').'0001';
				} else {
					$pin_code = substr($pin_code,-4);
					$pin_code = $pin_code + 1;
					$pin_code = date('Ymd').str_pad($pin_code,4,"0",STR_PAD_LEFT);
				}
				$array = array(
					'user_id' => $pin_userid, 'pin_authorized_by' => $user_id, 'pin_code' => $pin_code, 'pin_date_return' => NULL
				);
				$pin_id = $this->dbase->dataInsert('items_pinjam',$array);
				if (!$pin_id){
					$json['msg'] = 'DB Error';
				} else {
					//insert into pinjam detail
					$data_count = $i = 0;
					foreach($items_qty as $val){
						$val = (int)$val;
						if ($val > 0){
							$flag	= $items_flag[$i];
							$data_items = $this->dbase->dataRow('items',array('items_id'=>$items_id[$i]));
							if ($data_items){
								if ($data_items->$flag >= $val){
									$ar = array(
										'pin_id' => $pin_id, 'items_id' => $items_id[$i], $flag => $val
									);
									$this->dbase->dataInsert('items_pinjam_detail',$ar);
									$data_count++;
								}//end if ada stoknya
							}//end if data_items
						}//end if qty lebih dari 0
						$i++;
					}//end foreach items
					//insert into pinjam detail end
					if ($data_count == 0){
						$json['msg'] = 'Tidak ada data yang dimasukkan';
					} else {
						//tabel peminjam
						$sql = "";
						//tabel peminjam end
						$json['t'] = 1;
					}
				}//end if pin_id
			}//end validation
		}
		die(json_encode($json));
	}
}
