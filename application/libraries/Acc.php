<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Acc {
	function ac_type($type){
		switch($type){
			default	:
			case 1	: $text = 'Siswa'; break;
			case 2	: $text = 'Orang Tua / Wali Siswa'; break;
			case 3	: $text = 'Guru Mata Pelajaran'; break;
			case 4	: $text = 'Kurikulum'; break;
			case 5	: $text = 'Tata Usaha'; break;
			case 6	: $text = 'Bendahara'; break;
			case 80	: $text = 'Pengawas Ujian'; break;
			case 81	: $text = 'Proktor Ujian'; break;
			case 99	: $text = 'Super Admin'; break;
		}
		return $text;
	}
	function gr_type($type){
		switch($type){
			default	:
			case 1	: $text = 'Group Terbuka'; break;
			case 2	: $text = 'Group Tertutup'; break;
		}
		return $text;
	}
}