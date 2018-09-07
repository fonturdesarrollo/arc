<?php
	function cuenta_correlativo(){
		require('bd-pgsql.php');
		//$result = pg_query($conn, "SELECT id FROM constancias ORDER BY id DESC LIMIT 1 OFFSET 0;");
		//echo "SELECT id FROM constancias ORDER BY id DESC LIMIT 1 OFFSET 0;<br />";//die;
                $result = pg_query($conn, "SELECT last_value +1 FROM constancias_id_seq;");
                //echo "SELECT last_value +1 from constancias_id_seq;";
                if (!$result) {
			echo "<p>Error ejecutando consulta en bd-pg.</p>";
			exit;
		}
		$resultado = pg_fetch_array($result);                
		$resultado[0] = intval($resultado[0]);
		if($resultado[0]=='' || $resultado[0]==null){
			$resultado[0]='001';
		}else if($resultado[0]+1>1 && $resultado[0]+1<10){
			$resultado[0]='00'.strval($resultado[0]+1);
		}else if($resultado[0]+1>9&&$resultado[0]+1<100){
			$resultado[0]='0'.strval($resultado[0]+1);
		}
                return $resultado[0];  
	}
?>