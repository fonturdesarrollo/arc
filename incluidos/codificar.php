<?php
	function codificar($a, $b){
		$codificado=hash('crc32b',md5($a.$b.date('H:i:s')));
		return $codificado;
	}
?>