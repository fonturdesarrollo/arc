<?php   
require('bd-pgsql.php');
$contador = contar_visita($conn);
function contar_visita($conn){
	$id_contador = strtotime(date('01 F Y'));
        //$id_contador = 1;
	if (!isset($_SESSION['visita'])){
		$_SESSION['visita'] = time();
		$sql = pg_query($conn, "SELECT total FROM contador WHERE id_contador = $id_contador;");
		//$result = pg_query($sql);
		if ($RS = pg_fetch_row($sql)){
			$sql = pg_query($conn, "UPDATE contador SET total = ((SELECT total FROM contador WHERE id_contador = $id_contador)+1) WHERE id_contador = $id_contador;");
			//$result_updt = pg_query($sql);
		}else{
			$sql = pg_query($conn, "INSERT INTO contador (id_contador,total) VALUES ($id_contador,1);");
			//$result_insrt = $db->pg_query($sql);
		}
		pg_freeresult($sql);
	}
	$sql = pg_query($conn, "SELECT SUM(total) as total FROM contador;");
	//$result = pg_query($sql);
	$total = 0;
	if ($RS = pg_fetch_row($sql)){
		$total = $RS[0];
	}
	pg_freeresult($sql);
	return $total;
}
?>
