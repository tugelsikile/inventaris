<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enc{
	function passenc($password,$email){
		return md5($email.$password.md5($password.$email));
	}
	function pasHash($password) {
		define ('K_STRONG_PASSWORD_ENCRYPTION', true);
		if (defined('K_STRONG_PASSWORD_ENCRYPTION') AND K_STRONG_PASSWORD_ENCRYPTION) {
			$pswlen = strlen($password);
			$salt = (2 * $pswlen);
			for ($i = 0; $i < $pswlen; ++$i) {
				$salt += (($i + 1) * ord($password[$i]));
			}
			$hash = '$'.$salt.'#'.strrev($password).'$';
			return md5($hash);
		}
		return md5($password);
	}
	function genPass($length=FALSE){
		if (!$length){
			$length 	= 8;
		}
		$characters = '0123456789ABCDEFGHJKLMNPRSTUVWXY*';
		$string 	= "";
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[rand(0, strlen($characters)-1)];
		}
		return $string;
	}
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
		
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}
