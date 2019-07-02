<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kode_negara extends MY_Controller {
	function __construct(){
		parent::__construct();
	}
	function index(){
		$this->load->view('tmp/kode_negara');
	}
	function submit_kode(){
		//die(var_dump($_FILES['file']));
		
		ini_set('max_execution_time', 1000); //300 seconds = 5 minutes
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory','conv'));
		$inputFileName = FCPATH.'Book1.xlsx';
		//die(var_dump($inputFileName));
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
			$cacheSettings = array( 'memoryCacheSize' => '2GB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

			$inputFileType 	= IOFactory::identify($inputFileName); 
			//$objReader 		= IOFactory::createReader($inputFileType);
			$objReader = IOFactory::createReader('Excel2007');
			$objReader->setReadDataOnly(true);
			$objPHPExcel 	= $objReader->load($inputFileName);
			//$objPHPExcel 	= $objReader->load("./forms/test.xlsx");
//		try { 
//			$inputFileType = IOFactory::identify($inputFileName);
//			$objReader = IOFactory::createReader($inputFileType);
//			$objPHPExcel = $objReader->load($inputFileName);
//		} catch(Exception $e) { 
//			$json['msg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
//		}//end try
	
		//  Get worksheet dimensions
		$sheet 			= $objPHPExcel->getSheet(0);
		$highestRow 	= $sheet->getHighestRow();
//		$highestColumn 	= $sheet->getHighestColumn();
		//$highestRow	= 15;
		//$highestRow	= 9150;
		for ($row = 8; $row <= $highestRow; $row++){
			$sheet			= $objPHPExcel->getActiveSheet();
			$golongan		= $sheet->getCell('A'.$row)->getValue();
			$bidang			= $sheet->getCell('B'.$row)->getValue();
			$kelompok		= $sheet->getCell('C'.$row)->getValue();
			$subkelompok	= $sheet->getCell('D'.$row)->getValue();
			$subsubkelompok	= $sheet->getCell('E'.$row)->getValue();
			$nama			= strtoupper($sheet->getCell('F'.$row)->getValue());

			if (strlen(trim($golongan)) > 0){
				$bintang = 0;
				if (trim($subsubkelompok) == '*')	{ $bintang++; }
				if (trim($subkelompok) == '*')		{ $bintang++; }
				if (trim($kelompok) == '*')			{ $bintang++; }
				if (trim($bidang) == '*')			{ $bintang++; }
				
				if ($bintang == 4){ //jika (*)nya ada 4, masukkan ke tabel golongan
					$kng_id		= str_pad($golongan,2,"0",STR_PAD_LEFT);
					$array		= array('kng_id'=>$kng_id,'kng_name'=>$nama);
					$data 		= $this->dbase->dataRow('koneg_golongan',$array);
					if (!$data){
						$this->dbase->dataInsert('koneg_golongan',$array);
					}
				} elseif ($bintang == 3){ //jika (*)nya ada 3, masukkan ke tabel bidang
					$kng_id		= str_pad($golongan,2,"0",STR_PAD_LEFT);
					$knb_id		= str_pad($bidang,2,"0",STR_PAD_LEFT);
					$array		= array(
						'kng_id' => $kng_id, 'knb_id' => $knb_id, 'knb_name' => $nama
					);
					$data		= $this->dbase->dataRow('koneg_bidang',$array);
					if (!$data){
						$this->dbase->dataInsert('koneg_bidang',$array);
					}
				} elseif ($bintang == 2){ //jika (*)nya ada 2, masukkan ke tabel kelompok
					$kng_id		= str_pad($golongan,2,"0",STR_PAD_LEFT);
					$knb_id		= str_pad($bidang,2,"0",STR_PAD_LEFT);
					$knk_id		= str_pad($kelompok,2,"0",STR_PAD_LEFT);
					$array		= array(
						'kng_id' => $kng_id, 'knb_id' => $knb_id, 'knk_id' => $knk_id, 'knk_name' => $nama
					);
					$data 		= $this->dbase->dataRow('koneg_kelompok',$array);
					if (!$data){
						$this->dbase->dataInsert('koneg_kelompok',$array);
					}
				} elseif ($bintang == 1){ //jika (*)nya ada 1, masukkan ke tabel sub kelompok
					$kng_id		= str_pad($golongan,2,"0",STR_PAD_LEFT);
					$knb_id		= str_pad($bidang,2,"0",STR_PAD_LEFT);
					$knk_id		= str_pad($kelompok,2,"0",STR_PAD_LEFT);
					$knsk_id	= str_pad($subkelompok,2,"0",STR_PAD_LEFT);
					$array		= array(
						'kng_id' => $kng_id, 'knb_id' => $knb_id, 'knk_id' => $knk_id, 'knsk_id' => $knsk_id, 'knsk_name' => $nama
					);
					$data		= $this->dbase->dataRow('koneg_subkelompok',$array);
					if (!$data){
						$this->dbase->dataInsert('koneg_subkelompok',$array);
					}
				} else { //jika (*)nya tidak ada, masukkan ke tabel sub sub kelompok
					$kng_id		= str_pad($golongan,2,"0",STR_PAD_LEFT);
					$knb_id		= str_pad($bidang,2,"0",STR_PAD_LEFT);
					$knk_id		= str_pad($kelompok,2,"0",STR_PAD_LEFT);
					$knsk_id	= str_pad($subkelompok,2,"0",STR_PAD_LEFT);
					$knssk_id	= str_pad($subsubkelompok,2,"0",STR_PAD_LEFT);
					if ($nama == 'LAIN-LAIN'){ $knssk_id = 99; }
					$array		= array(
						'kng_id' => $kng_id, 'knb_id' => $knb_id, 'knk_id' => $knk_id, 'knsk_id' => $knsk_id,
						'knssk_id' => $knssk_id, 'knssk_name' => $nama
					);
					$data		= $this->dbase->dataRow('koneg_subsubkelompok',$array);
					if (!$data){
						$this->dbase->dataInsert('koneg_subsubkelompok',$array);
					}
				}//end if bintang
				
			}//end if
		}//end for row
	}
}
