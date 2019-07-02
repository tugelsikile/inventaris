<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Conv {
	function toNum($str) {
		$limit = 5; //apply max no. of characters
		$colLetters = strtoupper($str); //change to uppercase for easy char to integer conversion
		$strlen = strlen($colLetters); //get length of col string
		if($strlen > $limit)	return "Column too long!"; //may catch out multibyte chars in first pass
		preg_match("/^[A-Z]+$/",$colLetters,$matches); //check valid chars
		if(!$matches)return "Invalid characters!"; //should catch any remaining multibyte chars or empty string, numbers, symbols
		$it = 0; $vals = 0; //just start off the vars
		for($i=$strlen-1;$i>-1;$i--){ //countdown - add values from righthand side
			$vals += (ord($colLetters[$i]) - 64 ) * pow(26,$it); //cumulate letter value
			$it++; //simple counter
		}
		return $vals; //this is the answer
	}
	
	function toStr($n,$case = 'upper') {
		$alphabet   = array(
			'A',	'B',	'C',	'D',	'E',	'F',	'G',
			'H',	'I',	'J',	'K',	'L',	'M',	'N',
			'O',	'P',	'Q',	'R',	'S',	'T',	'U',
			'V',	'W',	'X',	'Y',	'Z'
		);
		$n 			= $n;
		if($n <= 26){
			$alpha 	=  $alphabet[$n-1];
		} elseif($n > 26) {
			$dividend   = ($n);
			$alpha      = '';
			$modulo;
			while($dividend > 0){
				$modulo     = ($dividend - 1) % 26;
				$alpha      = $alphabet[$modulo].$alpha;
				$dividend   = floor((($dividend - $modulo) / 26));
			}
		}
		if($case=='lower'){
			$alpha = strtolower($alpha);
		}
		return $alpha;
	} 
	function romawi($integer, $upcase = true) {
		$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
		$return = ''; 
		while($integer > 0) 
		{ 
			foreach($table as $rom=>$arb) 
			{ 
				if($integer >= $arb) 
				{ 
					$integer -= $arb; 
					$return .= $rom; 
					break; 
				} 
			} 
		} 
		return $return; 
	} 
	function hariIndo($date){
		$hariIndo 	= array("Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Minggu");
		return $hariIndo[(int)$date-1];
	}
	function bulanIndo($date){
		$BulanIndo	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
		return $BulanIndo[(int)$date-1];
	}
	function tglIndo($date){
		$BulanIndo	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
		
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
		return($result);
	}
	function items_jenis($jenis){
		switch($jenis){
			default :
			NULL	:
			case 1	: $jenis = 'Alat'; break;
			case 2	: $jenis = 'Bahan'; break;
		}
		return $jenis;
	}
	function user_level($jenis){
		switch($jenis){
			default :
			NULL	:
			case 1	: $jenis = 'Counter'; break;
			case 99	: $jenis = 'Admin'; break;
		}
		return $jenis;
	}
	function statuspeminjaman($jenis){
		switch($jenis){
			default :
			NULL	:
			case 0	: $jenis = 'belum dikembalikan'; break;
			case 1	: $jenis = 'dikembalikan dgn catatan'; break;
			case 2	: $jenis = 'sudah dikembalikan'; break;
		}
		return $jenis;
	}
	function statuspengajuan($jenis){
		switch($jenis){
			default :
			NULL	:
			case 1	: $jenis = 'Telah Dibuat'; break;
			case 2	: $jenis = 'Telah Diajukan'; break;
			case 3	: $jenis = 'Ditolak'; break;
			case 4	: $jenis = 'Diterima'; break;
		}
		return $jenis;
	}
}