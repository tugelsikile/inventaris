<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Controller {
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
			$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
			$data['body'] = 'manage_items';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
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
			if (!$order){ $order = 'items_name'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql_cat = $sql_jenis = "";
			$cat_id		= $this->input->post('cat_id');
			if ($cat_id){ $sql_cat = " AND ite.cat_id = '".$cat_id."' "; }
			$jenis		= $this->input->post('jenis');
			if ($jenis){ $sql_jenis = " AND ite.items_type = '".$jenis."' "; }
			$sql = "SELECT	ite.*,bra.brand_name,cat.cat_name,sat.sat_name
					FROM	tkj_items AS ite
					LEFT JOIN tkj_items_brand AS bra ON ite.brand_id = bra.brand_id 
					LEFT JOIN tkj_items_category AS cat ON ite.cat_id = cat.cat_id 
					LEFT JOIN tkj_items_satuan AS sat ON ite.sat_id = sat.sat_id 
					WHERE	(
							ite.items_name LIKE '%".$keyword."%' OR
							ite.items_description LIKE '%".$keyword."%' OR
							ite.items_model LIKE '%".$keyword."%' OR
							cat.cat_name LIKE '%".$keyword."%' OR
							bra.brand_name LIKE '%".$keyword."%' OR
							sat.sat_name LIKE '%".$keyword."%'
							)
							AND ite.items_status = 1 ".$sql_cat." ".$sql_jenis."
					GROUP BY ite.items_id
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT ite.items_id
					FROM	tkj_items AS ite
					LEFT JOIN tkj_items_brand AS bra ON ite.brand_id = bra.brand_id 
					LEFT JOIN tkj_items_category AS cat ON ite.cat_id = cat.cat_id 
					LEFT JOIN tkj_items_satuan AS sat ON ite.sat_id = sat.sat_id 
					WHERE	(
							ite.items_name LIKE '%".$keyword."%' OR
							ite.items_description LIKE '%".$keyword."%' OR
							ite.items_model LIKE '%".$keyword."%' OR
							cat.cat_name LIKE '%".$keyword."%' OR
							bra.brand_name LIKE '%".$keyword."%' OR
							sat.sat_name LIKE '%".$keyword."%'
							)
							AND ite.items_status = 1 ".$sql_cat." ".$sql_jenis."
					GROUP BY ite.items_id
					ORDER BY ".$order." ".$direction." ";
			$data_items	= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_items){
				$json['msg'] = 'Tidak ada data barang';
			} else {
				$i = 0;
				while(list(,$val) = each($data_items)){
					$data_items[$i] = $val;
					$sql = "SELECT	SUM(pd.items_rusak_total) AS rusak_total,SUM(pd.items_rusak_sedang) AS rusak,
									SUM(pd.items_normal) AS normal,SUM(pd.items_baru) AS baru
							FROM	tkj_items_pinjam_detail AS pd
							WHERE	pd.items_id = '".$val->items_id."' AND pd.pind_status < 2 ";
					$data_items[$i]->pinjam = $this->dbase->sqlRow($sql);
					$i++;
				}
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_items;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_items_data',$data,TRUE);
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
			$data['satuan'] = $this->dbase->dataResult('items_satuan',array('sat_status'=>1));
			$data['brand'] = $this->dbase->dataResult('items_brand',array('brand_status'=>1));
			$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
			$this->load->view('form/items_new',$data);
		}
	}
	function new_data_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$jenis		= $this->input->post('jenis');
			$kategori	= $this->input->post('kategori');
			$data_kat	= $this->dbase->dataRow('items_category',array('cat_id'=>$kategori,'cat_status'=>1));
			$brand		= $this->input->post('brand');
			$data_brand	= $this->dbase->dataRow('items_brand',array('brand_id'=>$brand,'brand_status'=>1));
			$model		= $this->input->post('model');
			$nama		= $this->input->post('nama');
			$descr		= $this->input->post('descr');
			$rustot		= (int)$this->input->post('rustot');
			$rusak		= (int)$this->input->post('rusak');
			$norm		= (int)$this->input->post('norm');
			$baru		= (int)$this->input->post('baru');
			$stock		= $rustot + $rusak + $norm + $baru;
			$satuan		= $this->input->post('satuan');
			$data_sat	= $this->dbase->dataRow('items_satuan',array('sat_id'=>$satuan,'sat_status'=>1));
			if (!$kategori || !$data_kat){
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
//			} elseif ($stock == 0){
//				$json['msg'] = 'Masukkan <strong>jumlah barang</strong>';
//				$json['elem'] = 'stock';
			} elseif (!$satuan || !$data_sat){
				$json['msg'] = '<strong>Satuan barang </strong>tidak valid';
				$json['elem'] = 'satuan';
			} else {
				$kode = "SELECT MAX(items_code) AS items_code FROM tkj_items WHERE MONTH(items_date) = MONTH(NOW()) ";
				$kode = $this->dbase->sqlRow($kode);
				if (!$kode){
					$kode = date('Ymd').'0000001';
				} else {
					$kode = substr($kode->items_code,-7);
					$kode = (int)$kode + 1;
					$kode = date('Ymd').str_pad($kode,7,"0",STR_PAD_LEFT);
				}
				if (!$data_brand){
					$brand_id = NULL; $brand_name = '';
				} else {
					$brand_id = $brand;
					$brand_name = $data_brand->brand_name;
				}
				$array = array(
					'items_code' => $kode, 'items_name' => $nama, 'cat_id' => $kategori, 'items_description' => $descr,
					'items_stock' => $stock, 'items_rusak_total' => $rustot,
					'items_rusak_sedang' => $rusak, 'items_normal' => $norm, 'items_baru' => $baru, 'sat_id' => $satuan,
					'brand_id' => $brand_id, 'items_model' => $model, 'items_type' => $jenis
				);
				$items_id = $this->dbase->dataInsert('items',$array);
				if (!$items_id){
					$json['msg'] = 'DB Error';
				} else {
					$this->load->library('conv');
					$data['data'] = $this->dbase->dataRow('items',array('items_id'=>$items_id));
					$data['data']->cat_name = $data_kat->cat_name;
					$data['data']->brand_name = $brand_name;
					$data['data']->sat_name = $data_sat->sat_name;
					$data['data']->pinjam = new stdClass;
					$data['data']->pinjam->rusak_total = $data['data']->pinjam->rusak = $data['data']->pinjam->normal =
					$data['data']->pinjam->baru = 0;
					$json['html'] = $this->load->view('manage_items_row',$data,TRUE);
					$json['t'] = 1;
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
			$data_items	= $this->dbase->dataRow('items',array('items_id'=>$items_id,'items_status'=>1));
			if (!$data_items || !$items_id){
				die('Invalid data barang');
			} else {
				$data['satuan'] = $this->dbase->dataResult('items_satuan',array('sat_status'=>1));
				$data['brand'] = $this->dbase->dataResult('items_brand',array('brand_status'=>1));
				$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
				$data['data'] = $data_items;
				$this->load->view('form/items_edit',$data);
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
			$items_id	= $this->input->post('items_id');
			$data_items	= $this->dbase->dataRow('items',array('items_id'=>$items_id,'items_status'=>1));
			$jenis		= $this->input->post('jenis');
			$kategori	= $this->input->post('kategori');
			$data_kat	= $this->dbase->dataRow('items_category',array('cat_id'=>$kategori,'cat_status'=>1));
			$brand		= $this->input->post('brand');
			$data_brand	= $this->dbase->dataRow('items_brand',array('brand_id'=>$brand,'brand_status'=>1));
			$model		= $this->input->post('model');
			$nama		= $this->input->post('nama');
			$descr		= $this->input->post('descr');
			$rustot		= (int)$this->input->post('rustot');
			$rusak		= (int)$this->input->post('rusak');
			$norm		= (int)$this->input->post('norm');
			$baru		= (int)$this->input->post('baru');
			$stock		= $rustot + $rusak + $norm + $baru;
			$satuan		= $this->input->post('satuan');
			$data_sat	= $this->dbase->dataRow('items_satuan',array('sat_id'=>$satuan,'sat_status'=>1));
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data barang';
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
//			} elseif ($stock == 0){
//				$json['msg'] = 'Masukkan <strong>jumlah barang</strong>';
//				$json['elem'] = 'stock';
			} elseif (!$satuan || !$data_sat){
				$json['msg'] = '<strong>Satuan barang </strong>tidak valid';
				$json['elem'] = 'satuan';
			} else {
				if (!$data_brand){
					$brand_id = NULL; $brand_name = '';
				} else {
					$brand_id = $brand;
					$brand_name = $data_brand->brand_name;
				}
				$array = array(
					'items_name' => $nama, 'cat_id' => $kategori, 'items_description' => $descr,
					'items_stock' => $stock, 'items_rusak_total' => $rustot,
					'items_rusak_sedang' => $rusak, 'items_normal' => $norm, 'items_baru' => $baru, 'sat_id' => $satuan,
					'brand_id' => $brand_id, 'items_model' => $model, 'items_type' => $jenis
				);
				$this->dbase->dataUpdate('items',array('items_id'=>$items_id),$array);
				$this->load->library('conv');
				$data['data'] = $this->dbase->dataRow('items',array('items_id'=>$items_id));
				$data['data']->cat_name = $data_kat->cat_name;
				$data['data']->brand_name = $brand_name;
				$data['data']->sat_name = $data_sat->sat_name;
				$sql = "SELECT	SUM(pd.items_rusak_total) AS rusak_total,SUM(pd.items_rusak_sedang) AS rusak,
								SUM(pd.items_normal) AS normal,SUM(pd.items_baru) AS baru
						FROM	tkj_items_pinjam_detail AS pd
						WHERE	pd.items_id = '".$items_id."' AND pd.pind_status < 2 ";
				$data['data']->pinjam = $this->dbase->sqlRow($sql);
				$json['id'] = $items_id;
				$json['html'] = $this->load->view('manage_items_row',$data,TRUE);
				$json['t'] = 1;
			}
		}
		die(json_encode($json));
	}
	function delete_data(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat halaman ini';
		} else {
			$json['t'] = 0;
			$items_id	= $this->input->post('id');
			$data_items	= $this->dbase->dataRow('items',array('items_id'=>$items_id,'items_status'=>1),'items_id');
			if (!$items_id || !$data_items){
				$json['msg'] = 'Invalid data barang';
			} else {
				$this->dbase->dataUpdate('items',array('items_id'=>$items_id),array('items_status'=>0));
				$json['t'] = 1;
				$json['msg'] = 'Barang berhasil dihapus';
			}
		}
		die(json_encode($json));
	}
	////////////////////////// PINJAM
	function pinjam(){
		if (!$this->session->userdata('inv_id')){
			$data['body'] = 'account/signin';
		} else {
			$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
			$data['body'] = 'manage_pinjam';
		}
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function pinjam_data_table(){
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
			if (!$order){ $order = 'pin_date'; }
			$direction	= $this->input->post('dir');
			if (!$direction){ $direction = 'ASC'; }
			$limit		= 20;
			$offset		= $limit * ( $page - 1);
			$sql_tgl = "";
			$tanggal		= $this->input->post('tanggal');
			if ($tanggal){ $sql_tgl = " AND DATE(pin.pin_date) = DATE('".$tanggal."')"; }
			
			$sql = "SELECT	pin.*,us1.user_fullname AS peminjam,us2.user_fullname AS yangpinjam
					FROM	tkj_items_pinjam AS pin
					LEFT JOIN tkj_user AS us1 ON pin.pin_authorized_by = us1.user_id
					LEFT JOIN tkj_user AS us2 ON pin.user_id = us2.user_id
					WHERE	(
							us1.user_fullname LIKE '%".$keyword."%' OR
							us2.user_fullname LIKE '%".$keyword."%'
							) ".$sql_tgl."
					GROUP BY pin.pin_id
					ORDER BY ".$order." ".$direction." LIMIT ".$offset.",".$limit." ";
			$next = "SELECT	pin.pin_id
					FROM	tkj_items_pinjam AS pin
					LEFT JOIN tkj_user AS us1 ON pin.pin_authorized_by = us1.user_id
					LEFT JOIN tkj_user AS us2 ON pin.user_id = us2.user_id
					WHERE	(
							us1.user_fullname LIKE '%".$keyword."%' OR
							us2.user_fullname LIKE '%".$keyword."%'
							) ".$sql_tgl."
					GROUP BY pin.pin_id
					ORDER BY ".$order." ".$direction." ";
			$data_pinjam= $this->dbase->sqlResult($sql);
			$next		= $this->dbase->sqlResult($next);
			$json['next'] 	= count($next);
			$json['page'] 	= $page;
			if ($direction == 'ASC'){ $json['dir'] = 'DESC'; } else { $json['dir'] = 'ASC'; }
			if (!$data_pinjam){
				$json['msg'] = 'Tidak ada data Peminjaman';
			} else {
				$i = 0;
				while(list(,$val) = each($data_pinjam)){
					$data_pinjam[$i] = $val;
					$sql = "SELECT	pind.*,ite.items_name
							FROM	tkj_items_pinjam_detail AS pind
							LEFT JOIN tkj_items AS ite ON pind.items_id = ite.items_id
							WHERE	pind.pin_id = '".$val->pin_id."' ";
					$data_pinjam[$i]->pinjam = $this->dbase->sqlResult($sql);
					$i++;
				}
				$json['t']		= 1;
				$this->load->library('conv');
				$data['data']	= $data_pinjam;
				$data['limit']	= $limit;
				$data['next']	= count($next);
				$data['page']	= $page;
				$json['html'] 	= $this->load->view('manage_pinjam_data',$data,TRUE);
			}
		}
		die(json_encode($json));
	}
	function pinjam_return($pin_id){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items/pinjam'));
		} else {
			$sql = "SELECT	pin.*,us1.user_fullname AS peminjam,us2.user_fullname AS yangpinjam
					FROM	tkj_items_pinjam AS pin
					LEFT JOIN tkj_user AS us1 ON pin.pin_authorized_by = us1.user_id
					LEFT JOIN tkj_user AS us2 ON pin.user_id = us2.user_id
					WHERE	pin.pin_id = '".$pin_id."' 
					GROUP BY pin.pin_id";
			//$pin_data = $this->dbase->dataRow('items_pinjam',array('pin_id'=>$pin_id));
			$pin_data = $this->dbase->sqlRow($sql);
			if (!$pin_id || !$pin_data){
				die('Invalid data peminjaman');
			} else {
				$data['data'] = $pin_data;
				$sql = "SELECT	pind.*,ite.items_name
							FROM	tkj_items_pinjam_detail AS pind
							LEFT JOIN tkj_items AS ite ON pind.items_id = ite.items_id
							WHERE	pind.pin_id = '".$pin_id."' ";
				$data['items'] = $this->dbase->sqlResult($sql);
				$this->load->view('form/pinjam_return',$data);
			}
		}
	}
	function pinjam_return_submit(){
		if (!$this->input->is_ajax_request()){
			redirect(base_url('items'));
		} elseif (!$this->session->userdata('inv_id')) {
			$json['t'] = 0; $json['msg'] = 'Tidak boleh lihat';
		} else {
			$json['t'] = 0;
			$pin_id		= $this->input->post('pin_id');
			$data_pin	= $this->dbase->dataRow('items_pinjam',array('pin_id'=>$pin_id));
			$pind_id	= $this->input->post('pind_id');
			$rustot		= $this->input->post('rustot');
			$rus		= $this->input->post('rus');
			$nor		= $this->input->post('nor');
			$bar		= $this->input->post('bar');
			$notes		= $this->input->post('notes');
			if (!$data_pin || !$pin_id){
				$json['msg'] = 'Invalid data Peminjaman';
			} elseif (count($pind_id) == 0){
				$json['msg'] = 'tidak ada data peminjaman';
			} else {
				//validate textarea
				$err = 0;
				foreach($notes as $val){
					if (strlen(trim($val)) == 0){
						$json['msg'] = 'Mohon isikan catatannya';
						$err++;
					}
				}
				if ($err > 0){
					die(json_encode($json));
				}
				//validate textarea end
				$i = $errGlog = 0;
				foreach($pind_id as $val){
					$err = 0;
					$data_pind = $this->dbase->dataRow('items_pinjam_detail',array('pind_id'=>$val));
					if ($data_pind){
						if ($data_pind->items_rusak_total > 0 ){
							if ($data_pind->items_rusak_total > $rustot[$i]){
								$err++;
								$errGlog++;
							}
						}
						if ($data_pind->items_rusak_sedang > 0 ){
							if ($data_pind->items_rusak_sedang > $rus[$i]){
								$err++;
								$errGlog++;
							}
						}
						if ($data_pind->items_normal > 0 ){
							if ($data_pind->items_normal > $nor[$i]){
								$err++;
								$errGlog++;
							}
						}
						if ($data_pind->items_baru > 0 ){
							if ($data_pind->items_baru > $bar[$i]){
								$err++;
								$errGlog++;
							}
						}
						if ($err > 0){
							$arr = array(
								'pind_notes' => $notes[$i], 'pind_status' => 1
							);
						} else {
							$arr = array(
								'pind_notes' => $notes[$i], 'pind_status' => 2
							);
						}
						$this->dbase->dataUpdate('items_pinjam_detail',array('pind_id'=>$val),$arr);
					}//end if data pind_id
					$i++;
				}//end foreach
				if ($errGlog > 0){
					$arr2 = array(
						'pin_notes' => 'Beberapa barang dikembalikan dengan catatan',
						'pin_status' => 1, 'pin_date_return' => date('Y-m-d H:i:s')
					);
					$status = 1;
				} else {
					$arr2 = array(
						'pin_status' => 2, 'pin_date_return' => date('Y-m-d H:i:s')
					);
					$status = 2;
				}
				$this->load->library('conv');
				$this->dbase->dataUpdate('items_pinjam',array('pin_id'=>$pin_id),$arr2);
				$json['t'] = 2; $json['id'] = $pin_id;
				$json['status'] = $this->conv->statuspeminjaman($status);
				$json['hide'] = $status;
			}//end validation
		}
		die(json_encode($json));
	}
	function print_data(){
		$data['body'] = 'items_print';
		$data['cat'] = $this->dbase->dataResult('items_category',array('cat_status'=>1));
		if ($this->input->is_ajax_request()){
			$this->load->view($data['body'],$data);
		} else {
			$this->load->view('home_page',$data);
		}
	}
	function items_print(){
		$keyword	= $this->input->post('table_search');
		$sql_cat = $sql_jenis = $sql_tgl = "";
		$cat_id		= $this->input->post('cat');
		if ($cat_id){ $sql_cat = " AND ite.cat_id = '".$cat_id."' "; }
		$jenis		= $this->input->post('type');
		if ($jenis){ $sql_jenis = " AND ite.items_type = '".$jenis."' "; }
		$date		= $this->input->post('tgl');
		if ($date){ $sql_tgl = " AND DATE(ite.items_date) = DATE('".$date."')"; }
		
		$sql = "SELECT	ite.*,bra.brand_name,cat.cat_name,sat.sat_name
					FROM	tkj_items AS ite
					LEFT JOIN tkj_items_brand AS bra ON ite.brand_id = bra.brand_id 
					LEFT JOIN tkj_items_category AS cat ON ite.cat_id = cat.cat_id 
					LEFT JOIN tkj_items_satuan AS sat ON ite.sat_id = sat.sat_id 
					WHERE	(
							ite.items_name LIKE '%".$keyword."%' OR
							ite.items_description LIKE '%".$keyword."%' OR
							ite.items_model LIKE '%".$keyword."%' OR
							cat.cat_name LIKE '%".$keyword."%' OR
							bra.brand_name LIKE '%".$keyword."%' OR
							sat.sat_name LIKE '%".$keyword."%'
							)
							AND ite.items_status = 1 ".$sql_cat." ".$sql_jenis." ".$sql_tgl."
					GROUP BY ite.items_id
					ORDER BY ite.items_code ASC ";
		$data['data'] = $this->dbase->sqlResult($sql);
		$this->load->library('conv');
		$this->load->view('print/print_items',$data);
	}
}
