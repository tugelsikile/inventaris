<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends MY_Controller {
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
			$data['peminjam'] = $this->dbase->dataResult('user',array('user_status'=>1,'user_level'=>0));
			$data['body'] = 'manage_pengajuan';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function insert_ajuan_from_items(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id') || $this->session->userdata('inv_level') != 99){
			$json['t'] = 0; $json['msg'] = 'Tidak boleh mengajukan';
		} else {
			$json['t']	= 0;
			$items_id 	= $this->input->post('id');
			$data_items	= $this->dbase->dataRow('items',array('items_id'=>$items_id));
			$user_id	= $this->session->userdata('inv_id');
			$data_user	= $this->dbase->dataRow('user',array('user_id'=>$user_id));
			$pen_id		= $this->session->userdata('pen_id');
			$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data Items';
			} elseif (!$user_id || !$data_user){
				$json['msg'] = 'Invalid data user';
			} elseif (!$pen_id || !$data_pen){
				$json['msg'] = 'Aktifkan atau buat dulu sebuah pengajuan di halaman data pengajuan';
			} else {
				$data_cart = $this->dbase->dataRow('cart',array('pen_id'=>$pen_id,'items_id'=>$items_id));
				if ($data_cart){
					$array = array(
						'items_qty' => $data_cart->items_qty + 1
					);
					$this->dbase->dataUpdate('cart',array('cart_id'=>$data_cart->cart_id),$array);
					$cart_id = $data_cart->cart_id;
				} else {
					$kode = "SELECT MAX(cart_code) AS cart_code FROM tkj_cart WHERE DATE(cart_date) = DATE(NOW()) ";
					$kode = $this->dbase->sqlRow($kode)->cart_code;
					if (!$kode){
						$kode = date('Ymd').'0001';
					} else {
						$kode = substr($kode,-4);
						$kode = (int)$kode + 1;
						$kode = date('Ymd').str_pad($kode,4,"0",STR_PAD_LEFT);
					}
					$array = array(
						'cart_code' => $kode, 'sat_id' => $data_items->sat_id, 'pen_id' => $pen_id,
						'cat_id' => $data_items->cat_id, 'items_id' => $data_items->items_id,
						'items_name' => $data_items->items_name, 'items_qty' => 1, 'brand_id' => $data_items->brand_id,
						'items_model' => $data_items->items_model, 'items_description' => $data_items->items_description,
						'items_type' => $data_items->items_type
					);
					$cart_id = $this->dbase->dataInsert('cart',$array);
				}
				if (!$cart_id){
					$json['msg'] = 'DB Error';
				} else {
					$json['msg'] = 'Sukses';
					$json['t'] = 1;
				}
			}
		}
		die(json_encode($json));
	}
	function data_table(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$keyword	= $this->input->post('keyword');
			$page		= $this->input->post('page');
			if (!$page){ $page = 1; }
			$order		= $this->input->post('order');
			if (!$order){ $order = 'pen_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql_tgl = "";
			$date		= $this->input->post('tgl');
			if ($date){ $sql_tgl = " AND DATE(pe.pen_date) = DATE(NOW()) "; }
			
			$sql = "SELECT	pe.*,us.user_fullname,COUNT(ca.cart_id) AS count_cart
					FROM	tkj_pengajuan AS pe
					LEFT JOIN tkj_user AS us ON pe.user_id = us.user_id
					LEFT JOIN tkj_cart AS ca ON pe.pen_id = ca.pen_id
					WHERE	(
							us.user_fullname LIKE '%".$keyword."%' OR
							pe.pen_code LIKE '%".$keyword."%' OR
							pe.pen_name LIKE '%".$keyword."%'
							) AND pe.pen_status != 0 ".$sql_tgl."
					GROUP BY pe.pen_id
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT	pe.pen_id
					FROM	tkj_pengajuan AS pe
					LEFT JOIN tkj_user AS us ON pe.user_id = us.user_id
					WHERE	(
							us.user_fullname LIKE '%".$keyword."%' OR
							pe.pen_code LIKE '%".$keyword."%' OR
							pe.pen_name LIKE '%".$keyword."%'
							) AND pe.pen_status != 0 ".$sql_tgl."
					GROUP BY pe.pen_id
					ORDER BY ".$order." ".$direction." ";
			$data_items	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_items){
				$json['msg'] = 'Tidak ada data Pengajuan';
			} else {
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_items;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_pengajuan_data',$data,TRUE);
			}
		}
		die(json_encode($json));
	}
	function new_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$this->load->view('form/pengajuan_new');
		}
	}
	function new_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$user_id	= $this->session->userdata('inv_id');
			$data_user	= $this->dbase->dataRow('user',array('user_id'=>$user_id,'user_status'=>1,'user_level'=>99));
			$nama		= $this->input->post('nama');
			$notes		= $this->input->post('notes');
			if (!$user_id || !$data_user){
				$json['msg'] = 'Invalid data user';
			} elseif (strlen(trim($nama)) == 0){
				$json['msg'] = 'Masukkan <strong>Judul Pengajuan</strong>';
				$json['elem'] = 'nama';
			} elseif (strlen(trim($notes)) == 0){
				$json['msg'] = 'Masukkan <strong>Deskripsi Pengajuan</strong>';
				$json['elem'] = 'notes';
			} else {
				$kode = "SELECT MAX(pen_code) AS pen_code FROM tkj_pengajuan WHERE MONTH(pen_date) = MONTH(NOW()) ";
				$kode = $this->dbase->sqlRow($kode)->pen_code;
				if (!$kode){
					$kode = date('Ymd').'0001';
				} else {
					$kode = substr($kode->items_code,-4);
					$kode = (int)$kode + 1;
					$kode = date('Ymd').str_pad($kode,4,"0",STR_PAD_LEFT);
				}
				$array = array(
					'pen_code' => $kode, 'pen_name' => $nama, 'user_id' => $user_id, 'pen_notes' => $notes
				);
				$items_id = $this->dbase->dataInsert('pengajuan',$array);
				if (!$items_id){
					$json['msg'] = 'DB Error';
				} else {
					$this->load->library('conv');
					$data['data'] = $this->dbase->dataRow('pengajuan',array('pen_id'=>$items_id));
					$data['data']->count_cart = 0;
					$data['data']->user_fullname = $this->session->userdata('inv_fullname');
					$json['html'] = $this->load->view('manage_pengajuan_row',$data,TRUE);
					$json['t'] = 1;
					$this->session->set_userdata(array('pen_id'=>$items_id));
				}
			}
		}
		die(json_encode($json));
	}
	function edit_data($items_id){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$data_items	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$items_id));
			if (!$data_items || !$items_id){
				die('Invalid data Pengajuan');
			} else {
				$data['data'] = $data_items;
				$this->load->view('form/pengajuan_edit',$data);
			}
		}
	}
	function edit_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$user_id	= $this->session->userdata('inv_id');
			$data_user	= $this->dbase->dataRow('user',array('user_id'=>$user_id,'user_status'=>1,'user_level'=>99));
			$pen_id		= $this->input->post('pen_id');
			$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			$nama		= $this->input->post('nama');
			$notes		= $this->input->post('notes');
			if (!$pen_id || !$data_pen){
				$json['msg'] = 'Invalid data pengajuan';
			} elseif (!$user_id || !$data_user){
				$json['msg'] = 'Invalid data user';
			} elseif (strlen(trim($nama)) == 0){
				$json['msg'] = 'Masukkan <strong>Judul Pengajuan</strong>';
				$json['elem'] = 'nama';
			} elseif (strlen(trim($notes)) == 0){
				$json['msg'] = 'Masukkan <strong>Deskripsi Pengajuan</strong>';
				$json['elem'] = 'notes';
			} else {
				$array = array(
					'pen_name' => $nama, 'pen_notes' => $notes
				);
				$json['nama'] = $nama;	$json['id'] = $pen_id;
				$json['notes'] = $notes;
				$this->dbase->dataUpdate('pengajuan',array('pen_id'=>$pen_id),$array);
				$json['t'] = 1;
			}
		}
		die(json_encode($json));
	}
	function delete_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$items_id	= $this->input->post('id');
			$data_items	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$items_id),'pen_id');
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data pengajuan';
			} else {
				$this->dbase->dataUpdate('pengajuan',array('pen_id'=>$items_id),array('pen_status'=>0));
				$json['t'] = 1;
				$json['msg'] = 'Pengajuan berhasil dihapus';
			}
		}
		die(json_encode($json));
	}
	function set_active(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$items_id	= $this->input->post('id');
			$data_items	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$items_id),'pen_id');
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data pengajuan';
			} else {
				$this->session->set_userdata(array('pen_id'=>$items_id));
				$json['t'] = 1;
			}
		}
		die(json_encode($json));
	}
	////////////////////////// 
	function detail_pengajuan($pen_id){
		if (!$this->session->userdata('inv_id')){
			$data['body'] = 'account/signin';
		} else {
			$data_pen = $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			if (!$data_pen || !$pen_id){
				$data['body'] = 'errors/404';
			} else {
				$data['data'] = $data_pen;
				$sql = "SELECT COUNT(cart_id) AS count_cart FROM tkj_cart WHERE pen_id = '".$pen_id."' ";
				$data['data']->count_cart = $this->dbase->sqlRow($sql)->count_cart;
				$data['body'] = 'manage_detail_pengajuan';
			}
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function data_table_items(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$keyword	= $this->input->post('keyword');
			$page		= $this->input->post('page');
			if (!$page){ $page = 1; }
			$order		= $this->input->post('order');
			if (!$order){ $order = 'items_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$pen_id		= $this->input->post('pen_id');
			$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			if (!$data_pen){
				$json['msg'] = 'Invalid data pengajuan';
			} else {
				$sql = "SELECT	ca.*,bra.brand_name,sat.sat_name,cat.cat_name
						FROM	tkj_cart AS ca
						LEFT JOIN tkj_items_brand AS bra ON ca.brand_id = bra.brand_id
						LEFT JOIN tkj_items_satuan AS sat ON ca.sat_id = sat.sat_id
						LEFT JOIN tkj_items_category cat ON ca.cat_id = cat.cat_id
						WHERE	(
								ca.items_name LIKE '%".$keyword."%' OR
								ca.items_description LIKE '%".$keyword."%' OR
								bra.brand_name LIKE '%".$keyword."%' OR
								cat.cat_name LIKE '%".$keyword."%'
								) AND ca.pen_id = '".$pen_id."'
						GROUP BY ca.cart_id
						ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
				$next = "SELECT	ca.cart_id
						FROM	tkj_cart AS ca
						LEFT JOIN tkj_items_brand AS bra ON ca.brand_id = bra.brand_id
						LEFT JOIN tkj_items_category cat ON ca.cat_id = cat.cat_id
						WHERE	(
								ca.items_name LIKE '%".$keyword."%' OR
								ca.items_description LIKE '%".$keyword."%' OR
								bra.brand_name LIKE '%".$keyword."%' OR
								cat.cat_name LIKE '%".$keyword."%'
								) AND ca.pen_id = '".$pen_id."'
						GROUP BY ca.cart_id
						ORDER BY ".$order." ".$direction." ";
				$data_items	= $this->dbase->sqlResult($sql);
				$next		= $this->dbase->sqlResult($next);
				$json['next'] 	= count($next);
				$json['page'] 	= $page;
				if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
				if (!$data_items){
					$json['msg'] = 'Tidak ada data Barang';
				} else {
					$json['t']		= 1;
					$this->load->library('conv');
					$data['data']	= $data_items;
					$data['pen']	= $data_pen;
					$data['limit']	= $limit;
					$data['next']	= count($next);
					$data['page']	= $page;
					$json['html'] 	= $this->load->view('manage_detail_pengajuan_data',$data,TRUE);
				}
			}
		}
		die(json_encode($json));
	}
	function update_qty(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$cart_id	= $this->input->post('id');
			$data_cart	= $this->dbase->dataRow('cart',array('cart_id'=>$cart_id));
			$cart_qty	= $this->input->post('qty');
			if (!$cart_id || !$data_cart){
				$json['msg'] = 'Invalid data Cart';
			} else {
				$this->dbase->dataUpdate('cart',array('cart_id'=>$cart_id),array('items_qty'=>$cart_qty));
				$json['t'] = 1;
				$json['msg'] = 'Sukses';
			}
		}
		die(json_encode($json));
	}
	function update_price(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$cart_id	= $this->input->post('id');
			$data_cart	= $this->dbase->dataRow('cart',array('cart_id'=>$cart_id));
			$cart_qty	= (int)$this->input->post('price');
			if (!$cart_id || !$data_cart){
				$json['msg'] = 'Invalid data Cart';
			} else {
				$this->dbase->dataUpdate('cart',array('cart_id'=>$cart_id),array('cart_price'=>$cart_qty));
				$json['t'] = 1;
				$json['msg'] = 'Sukses';
			}
		}
		die(json_encode($json));
	}
	function update_notes(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('pengajuan'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] 	= 0;
			$cart_id	= $this->input->post('id');
			$data_cart	= $this->dbase->dataRow('cart',array('cart_id'=>$cart_id));
			$cart_qty	= $this->input->post('notes');
			if (!$cart_id || !$data_cart){
				$json['msg'] = 'Invalid data Cart';
			} else {
				$this->dbase->dataUpdate('cart',array('cart_id'=>$cart_id),array('cart_notes'=>$cart_qty));
				$json['t'] = 1;
				$json['msg'] = 'Sukses';
			}
		}
		die(json_encode($json));
	}
	function new_data_items($pen_id){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			die('Tidak boleh lihat halaman ini');
		} else {
			$data_pen = $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			if (!$data_pen || !$pen_id){
				die('Invalid data pengajuan');
			} else {
				$data['data']	= $data_pen;
				$data['satuan'] = $this->dbase->dataResult('items_satuan',array('sat_status'=>1));
				$data['brand'] = $this->dbase->dataResult('items_brand',array('brand_status'=>1));
				$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
				$this->load->view('form/pengajuan_items_new',$data);
			}
		}
	}
	function new_data_items_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$pen_id		= $this->input->post('pen_id');
			$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			$jenis		= $this->input->post('jenis');
			$kategori	= $this->input->post('kategori');
			$data_kat	= $this->dbase->dataRow('items_category',array('cat_id'=>$kategori,'cat_status'=>1));
			$brand		= $this->input->post('brand');
			$data_brand	= $this->dbase->dataRow('items_brand',array('brand_id'=>$brand,'brand_status'=>1));
			$model		= $this->input->post('model');
			$nama		= $this->input->post('nama');
			$descr		= $this->input->post('descr');
			$jumlah		= (int)$this->input->post('jumlah');
			$harga		= (int)$this->input->post('harga');
			$notes		= $this->input->post('notes');
			$satuan		= $this->input->post('satuan');
			$data_sat	= $this->dbase->dataRow('items_satuan',array('sat_id'=>$satuan,'sat_status'=>1));
			if (!$pen_id || !$data_pen){
				$json['msg'] = 'Aktifkan atau buat dulu sebuah pengajuan di halaman data pengajuan';
			} elseif (!$kategori || !$data_kat){
				$json['msg'] = 'Pilih <strong>Kategori</strong> barang';
				$json['elem'] = 'kategori';
			} elseif ($brand && !$data_brand){
				$json['msg'] = '<strong>Merek barang </strong>tidak valid';
				$json['elem'] = 'brand';
			} elseif (strlen(trim($nama)) == 0){
				$json['msg'] = 'Masukkan <strong>nama barang</strong>';
				$json['elem'] = 'nama';
			} elseif (strlen(trim($nama)) > 255){
				$json['msg'] = 'Nama barang terlalu panjang';
				$json['elem'] = 'nama';
			} elseif (strlen(trim($descr)) == 0){
				$json['msg'] = 'Masukkan <strong>deskripsi &amp; spesifikasi barang</strong>';
				$json['elem'] = 'descr';
			} elseif (!$jumlah || $jumlah == 0){
				$json['msg'] = 'Masukkan <strong>Jumlah Barang</strong>';
				$json['elem'] = 'jumlah';
			} elseif (!$harga || $harga == 0){
				$json['msg'] = 'Masukkan <strong>Kisaran Harga Barang</strong>';
				$json['elem'] = 'harga';
			} elseif (!$satuan || !$data_sat){
				$json['msg'] = '<strong>Satuan barang </strong>tidak valid';
				$json['elem'] = 'satuan';
			} elseif (strlen(trim($notes)) == 0){
				$json['msg'] = 'Masukkan <strong>Catatan pengajuan</strong>';
				$json['elem'] = 'notes';
			} else {
				$kode = "SELECT MAX(cart_code) AS cart_code FROM tkj_cart WHERE DATE(cart_code) = DATE(NOW()) ";
				$kode = $this->dbase->sqlRow($kode);
				if (!$kode){
					$kode = date('Ymd').'0000001';
				} else {
					$kode = substr($kode->cart_code,-4);
					$kode = (int)$kode + 1;
					$kode = date('Ymd').str_pad($kode,4,"0",STR_PAD_LEFT);
				}
				if (!$data_brand){
					$brand_id = NULL; $brand_name = '';
				} else {
					$brand_id = $brand;
					$brand_name = $data_brand->brand_name;
				}
				$array = array(
					'cart_code' => $kode, 'items_name' => $nama, 'cat_id' => $kategori, 'items_description' => $descr,
					'items_qty' => $jumlah, 'sat_id' => $satuan, 'pen_id' => $pen_id, 'cart_notes' => $notes,
					'brand_id' => $brand_id, 'items_model' => $model, 'items_type' => $jenis, 'cart_price' => $harga
				);
				$items_id = $this->dbase->dataInsert('cart',$array);
				if (!$items_id){
					$json['msg'] = 'DB Error';
				} else {
					$this->load->library('conv');
					$data['data'] = $this->dbase->dataRow('cart',array('cart_id'=>$items_id));
					$data['data']->cat_name = $data_kat->cat_name;
					$data['data']->brand_name = $brand_name;
					$data['data']->sat_name = $data_sat->sat_name;
					$json['html'] = $this->load->view('manage_detail_pengajuan_row',$data,TRUE);
					$json['t'] = 1;
				}
			}
		}
		die(json_encode($json));
	}
	function submit_pengajuan(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t']	= 0;
			$pen_id		= $this->input->post('id');
			$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
			if (!$pen_id || !$data_pen){
				$json['msg'] = 'Invalid data pengajuan';
			} else {
				$json['t'] = 1;
				$this->session->unset_userdata('pen_id');
				$this->dbase->dataUpdate('pengajuan',array('pen_id'=>$pen_id),array('pen_status'=>2));
			}
		}
		die(json_encode($json));
	}
	function cetak_pengajuan($pen_id){
		$data_pen	= $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
		if (!$pen_id || !$data_pen){
			$data['body'] = 'errors/404';
		} else {
			$data['data'] = $data_pen;
			$data['body'] = 'pengajuan_cetak';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function pengajuan_print($pen_id){
		$data_pen = $this->dbase->dataRow('pengajuan',array('pen_id'=>$pen_id));
		if (!$pen_id || !$data_pen){
			die('Tidak ada data pengajuan');
		} else {
			$sql = "SELECT	cart.*,brand.brand_name,cat.cat_name,sat.sat_name
					FROM	tkj_cart AS cart
					LEFT JOIN tkj_items_brand AS brand ON cart.brand_id = brand.brand_id
					LEFT JOIN tkj_items_category AS cat ON cart.cat_id = cat.cat_id
					LEFT JOIN tkj_items_satuan AS sat ON cart.sat_id = sat.sat_id
					WHERE	cart.pen_id = '".$pen_id."' 
					
					ORDER BY cart.cart_code ASC ";
			$data_cart = $this->dbase->sqlResult($sql);
			if (!$data_cart){
				die('Tidak ada data Barang dalam Pengajuan ini');
			} else {
				$data['data']	= $data_cart;
				$data['pen']	= $data_pen;
				$this->load->library('conv');
				$this->load->view('print/print_pengajuan',$data);
			}
		}
	}
}
