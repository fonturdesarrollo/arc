<?php
	if(isset($_POST['cuenta'])&&isset($_POST['ci'])&&isset($_POST['año'])&&isset($_POST['captcha_code'])){
	include '../incluidos/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			//echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=cvi">';
			//return 0;
		}
		$año=$_POST['año'];
		$esquema="SIMA0".substr($año,2,2);
		$numero_cuenta=$_POST['cuenta'];
		$cedula=$_POST['ci'];
		$total_asignaciones = 0;
		$total_deducciones = 0;
	}else{
		header('Location: ingreso.php');
	}
	
	function arregla_fecha($fecha){
		$arreglo_fecha = explode("-",$fecha);
		return $arreglo_fecha[2]."/".$arreglo_fecha[1]."/".$arreglo_fecha[0];
	}
	
	$conexion = pg_pconnect("host=172.16.7.195 port=5432 dbname=SIMA user=cidesa password=cidesa");
	if (!$conexion) {
		//echo "No se conectó a PostgreSQL."; exit;
	}

	$consulta = pg_query($conexion, "select * from \"$esquema\".nphiscon WHERE cedemp = '$cedula' AND cuenta_banco = '$numero_cuenta' and fecnom between '".$año."-01-01' and '".$año."-12-31' limit 1;");
	if (!$consulta) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	if(pg_num_rows($consulta)==0){
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=rne">'; exit;
	}

	while ($fila = pg_fetch_array($consulta)) {
		$codigo_nomina = $fila['codnom'];
		
		if($codigo_nomina=="004"){
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=rne">'; exit;
		}
		
		$cedula_empleado = $fila['cedemp'];
		
		$fecha_nomina = $fila['fecnom'];
		$nombre_empleado = $fila['nomemp'];
		
		$consulta1 = pg_query($conexion, "SELECT fecing FROM \"$esquema\".nphojint WHERE cedemp = '$cedula_empleado';");
		if (!$consulta1) {
			//echo "No se pudo ejecutar la consulta."; exit;
		}

		while ($fila1 = pg_fetch_array($consulta1)) {
			$fecha_ingreso = $fila1['fecing'];
		}
		
		$consulta2 = pg_query($conexion, "SELECT nomnom FROM \"$esquema\".npnomina WHERE codnom = '$codigo_nomina';");
		if (!$consulta2) {
			//echo "No se pudo ejecutar la consulta."; exit;
		}

		while ($fila2 = pg_fetch_array($consulta2)) {
			$nombre_nomina = $fila2['nomnom'];
		}
		
		$dependencia = $fila['desniv'];
		$nombre_cargo = $fila['nomcar'];
		$cuenta_banco = $fila['cuenta_banco'];
	}
	
	$contador_asignaciones = 1;
	$nombre_asignacion[255]=array("");
	$monto_asignacion[255]=array("");
	
	$remuneraciones_enero = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '01' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_enero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_enero=0;
	
	while ($total_enero = pg_fetch_array($remuneraciones_enero)) {
		$total_asignaciones_enero=floatval($total_enero["monto"]);
		if($total_asignaciones_enero==""||$total_asignaciones_enero==" "||$total_asignaciones_enero==null){
			
		}
	}
	
	$total_asignado_enero=$total_asignaciones_enero;
	
	$remuneraciones_febrero = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '02' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_febrero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_febrero=0;
	
	while ($total_febrero = pg_fetch_array($remuneraciones_febrero)) {
		$total_asignaciones_febrero=floatval($total_febrero["monto"]);
		if($total_asignaciones_febrero==""||$total_asignaciones_febrero==" "||$total_asignaciones_febrero==null){
			
		}
	}
	
	$total_asignado_febrero=floatval($total_asignado_enero)+floatval($total_asignaciones_febrero);
	
	$remuneraciones_marzo = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '03' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_marzo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_marzo=0;
	
	while ($total_marzo = pg_fetch_array($remuneraciones_marzo)) {
		$total_asignaciones_marzo=floatval($total_marzo["monto"]);
		if($total_asignaciones_marzo==""||$total_asignaciones_marzo==" "||$total_asignaciones_marzo==null){
			
		}
	}
	
	$total_asignado_marzo=$total_asignado_febrero+$total_asignaciones_marzo;
	
	$remuneraciones_abril = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '04' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_abril) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_abril=0;
	
	while ($total_abril = pg_fetch_array($remuneraciones_abril)) {
		$total_asignaciones_abril=floatval($total_abril["monto"]);
		if($total_asignaciones_abril==""||$total_asignaciones_abril==" "||$total_asignaciones_abril==null){
			
		}
	}
	
	$total_asignado_abril=$total_asignado_marzo+$total_asignaciones_abril;
	
	$remuneraciones_mayo = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '05' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_mayo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_mayo=0;
	
	while ($total_mayo = pg_fetch_array($remuneraciones_mayo)) {
		$total_asignaciones_mayo=floatval($total_mayo["monto"]);
		if($total_asignaciones_mayo==""||$total_asignaciones_mayo==" "||$total_asignaciones_mayo==null){
			
		}
	}
	
	$total_asignado_mayo=$total_asignado_abril+$total_asignaciones_mayo;
	
	$remuneraciones_junio = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '06' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_junio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_junio=0;
	
	while ($total_junio = pg_fetch_array($remuneraciones_junio)) {
		$total_asignaciones_junio=floatval($total_junio["monto"]);
		if($total_asignaciones_junio==""||$total_asignaciones_junio==" "||$total_asignaciones_junio==null){
			
		}
	}
	
	$total_asignado_junio=$total_asignado_mayo+$total_asignaciones_junio;
	
	$remuneraciones_julio = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '07' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_julio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_julio=0;
	
	while ($total_julio = pg_fetch_array($remuneraciones_julio)) {
		$total_asignaciones_julio=floatval($total_julio["monto"]);
		if($total_asignaciones_julio==""||$total_asignaciones_julio==" "||$total_asignaciones_julio==null){
			
		}
	}
	
	$total_asignado_julio=$total_asignado_junio+$total_asignaciones_julio;
	
	$remuneraciones_agosto = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '08' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_agosto) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_agosto=0;
	
	while ($total_agosto = pg_fetch_array($remuneraciones_agosto)) {
		$total_asignaciones_agosto=floatval($total_agosto["monto"]);
		if($total_asignaciones_agosto==""||$total_asignaciones_agosto==" "||$total_asignaciones_agosto==null){
			
		}
	}
	
	$total_asignado_agosto=$total_asignado_julio+$total_asignaciones_agosto;
	
	$remuneraciones_septiembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '09' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_septiembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_septiembre=0;
	
	while ($total_septiembre = pg_fetch_array($remuneraciones_septiembre)) {
		$total_asignaciones_septiembre=floatval($total_septiembre["monto"]);
		if($total_asignaciones_septiembre==""||$total_asignaciones_septiembre==" "||$total_asignaciones_septiembre==null){
			
		}
	}
	
	$total_asignado_septiembre=$total_asignado_agosto+$total_asignaciones_septiembre;
	
	$remuneraciones_octubre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '10' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_octubre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_octubre=0;
	
	while ($total_octubre = pg_fetch_array($remuneraciones_octubre)) {
		$total_asignaciones_octubre=floatval($total_octubre["monto"]);
		if($total_asignaciones_octubre==""||$total_asignaciones_octubre==" "||$total_asignaciones_octubre==null){
			
		}
	}
	
	$total_asignado_octubre=$total_asignado_septiembre+$total_asignaciones_octubre;
	
	$remuneraciones_noviembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '11' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_noviembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_noviembre=0;
	
	while ($total_noviembre = pg_fetch_array($remuneraciones_noviembre)) {
		$total_asignaciones_noviembre=floatval($total_noviembre["monto"]);
		if($total_asignaciones_noviembre==""||$total_asignaciones_noviembre==" "||$total_asignaciones_noviembre==null){
			
		}
	}
	
	$total_asignado_noviembre=$total_asignado_octubre+$total_asignaciones_noviembre;
	
	$remuneraciones_diciembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' /*AND cuenta_banco = '$numero_cuenta'*/ and extract(Month from fecnom) = '12' and extract(Year from fecnom) = '$año' and asided = 'A') and (codcon = '001' or codcon = '003' or codcon = '008' or codcon = '050' or codcon = '051' or codcon = '052' or codcon = '053' or codcon = '054' or codcon = '055' or codcon = '056' or codcon = '057' or codcon = '081' or codcon = '082' or codcon = '210' or codcon = '214' or codcon = '305' or codcon = '327' or codcon = '328' or codcon = '330' or codcon = '800' or codcon = '801' or codcon = '810' or codcon = '811' or codcon = '812' or codcon = '813' or codcon = '820' or codcon = '821' or codcon = '822' or codcon = '839' or codcon = '006' or codcon = '007' or codcon = '049' or codcon = '059');");
	if (!$remuneraciones_diciembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_asignaciones_diciembre=0;
	
	while ($total_diciembre = pg_fetch_array($remuneraciones_diciembre)) {
		$total_asignaciones_diciembre=floatval($total_diciembre["monto"]);
		if($total_asignaciones_diciembre==""||$total_asignaciones_diciembre==" "||$total_asignaciones_diciembre==null){
			
		}
	}
	
	$total_asignado_diciembre=$total_asignado_noviembre+$total_asignaciones_diciembre;
	
	//----- ----- ----- ----- ----- ----- ----- ----- ----- -----//
	//----- ----- ----- ----- ----- ----- ----- ----- ----- -----//
	//--- ----- ----- ----- FIN ASIGNACIONES ----- ----- ----- ---//
	//- ----- ----- ----- COMIENZO RETENCIONES ----- ----- ----- -//
	//----- ----- ----- ----- ----- ----- ----- ----- ----- -----//
	//----- ----- ----- ----- ----- ----- ----- ----- ----- -----//
	
	$retenciones_enero = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '01' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') group by cantidad;");
	if (!$retenciones_enero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_enero=0;
	$total_cantidad_enero=0;
	
	while ($total_r_enero = pg_fetch_array($retenciones_enero)) {
		$total_retenciones_enero=floatval($total_r_enero["monto"]);
		if($total_retenciones_enero==""||$total_retenciones_enero==" "||$total_retenciones_enero==null){
			
		}
	}
	
	$total_retenido_enero=$total_retenciones_enero;
	
	$cantidades_enero = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '01' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_enero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_enero = pg_fetch_array($cantidades_enero)) {
		$total_cantidad_enero=floatval($total_c_enero["cantidad"]);
	}
	
	$retenciones_febrero = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '02' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_febrero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_febrero=0;
	$total_cantidad_febrero=0;
	
	while ($total_r_febrero = pg_fetch_array($retenciones_febrero)) {
		$total_retenciones_febrero=floatval($total_r_febrero["monto"]);
		if($total_retenciones_febrero==""||$total_retenciones_febrero==" "||$total_retenciones_febrero==null){
			
		}
	}
	
	$total_retenido_febrero=$total_retenido_enero+$total_retenciones_febrero;
	
	$cantidades_febrero = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '02' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_febrero) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_enero = pg_fetch_array($cantidades_febrero)) {
		$total_cantidad_febrero=floatval($total_c_enero["cantidad"]);
	}
	
	$retenciones_marzo = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '03' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_marzo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_marzo=0;
	$total_cantidad_marzo=0;
	
	while ($total_r_marzo = pg_fetch_array($retenciones_marzo)) {
		$total_retenciones_marzo=floatval($total_r_marzo["monto"]);
		if($total_retenciones_marzo==""||$total_retenciones_marzo==" "||$total_retenciones_marzo==null){
			
		}
	}
	
	$total_retenido_marzo=$total_retenido_febrero+$total_retenciones_marzo;
	
	$cantidades_marzo = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '03' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_marzo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_marzo = pg_fetch_array($cantidades_marzo)) {
		$total_cantidad_marzo=floatval($total_c_marzo["cantidad"]);
	}
	
	$retenciones_abril = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '04' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_abril) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_abril=0;
	$total_cantidad_abril=0;
	
	while ($total_r_abril = pg_fetch_array($retenciones_abril)) {
		$total_retenciones_abril=floatval($total_r_abril["monto"]);
		if($total_retenciones_abril==""||$total_retenciones_abril==" "||$total_retenciones_abril==null){
			
		}
	}
	
	$total_retenido_abril=$total_retenido_marzo+$total_retenciones_abril;
	
	$cantidades_abril = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '04' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_abril) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_abril = pg_fetch_array($cantidades_abril)) {
		$total_cantidad_abril=floatval($total_c_abril["cantidad"]);
	}
	
	$retenciones_mayo = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '05' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_mayo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_mayo=0;
	$total_cantidad_mayo=0;
	
	while ($total_r_mayo = pg_fetch_array($retenciones_mayo)) {
		$total_retenciones_mayo=floatval($total_r_mayo["monto"]);
		if($total_retenciones_mayo==""||$total_retenciones_mayo==" "||$total_retenciones_mayo==null){
			
		}
	}
	
	$total_retenido_mayo=$total_retenido_abril+$total_retenciones_mayo;
	
	$cantidades_mayo = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '05' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_mayo) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_mayo = pg_fetch_array($cantidades_mayo)) {
		$total_cantidad_mayo=floatval($total_c_mayo["cantidad"]);
	}
	
	$retenciones_junio = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '06' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_junio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_junio=0;
	$total_cantidad_junio=0;
	
	while ($total_r_junio = pg_fetch_array($retenciones_junio)) {
		$total_retenciones_junio=floatval($total_r_junio["monto"]);
		if($total_retenciones_junio==""||$total_retenciones_junio==" "||$total_retenciones_junio==null){
			
		}
	}
	
	$total_retenido_junio=$total_retenido_mayo+$total_retenciones_junio;
	
	$cantidades_junio = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '06' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_junio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_junio = pg_fetch_array($cantidades_junio)) {
		$total_cantidad_junio=floatval($total_c_junio["cantidad"]);
	}
	
	$retenciones_julio = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '07' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_julio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_julio=0;
	$total_cantidad_julio=0;
	
	while ($total_r_julio = pg_fetch_array($retenciones_julio)) {
		$total_retenciones_julio=floatval($total_r_julio["monto"]);
		if($total_retenciones_julio==""||$total_retenciones_julio==" "||$total_retenciones_julio==null){
			
		}
	}
	
	$total_retenido_julio=$total_retenido_junio+$total_retenciones_julio;
	
	$cantidades_julio = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '07' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_julio) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_julio = pg_fetch_array($cantidades_julio)) {
		$total_cantidad_julio=floatval($total_c_julio["cantidad"]);
	}
	
	$retenciones_agosto = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '08' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_agosto) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_agosto=0;
	$total_cantidad_agosto=0;
	
	while ($total_r_agosto = pg_fetch_array($retenciones_agosto)) {
		$total_retenciones_agosto=floatval($total_r_agosto["monto"]);
		if($total_retenciones_agosto==""||$total_retenciones_agosto==" "||$total_retenciones_agosto==null){
			
		}
	}
	
	$total_retenido_agosto=$total_retenido_julio+$total_retenciones_agosto;
	
	$cantidades_agosto = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '08' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_agosto) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_agosto = pg_fetch_array($cantidades_agosto)) {
		$total_cantidad_agosto=floatval($total_c_agosto["cantidad"]);
	}
	
	$retenciones_septiembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '09' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_septiembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_septiembre=0;
	$total_cantidad_septiembre=0;
	
	while ($total_r_septiembre = pg_fetch_array($retenciones_septiembre)) {
		$total_retenciones_septiembre=floatval($total_r_septiembre["monto"]);
		if($total_retenciones_septiembre==""||$total_retenciones_septiembre==" "||$total_retenciones_septiembre==null){
			
		}
	}
	
	$total_retenido_septiembre=$total_retenido_agosto+$total_retenciones_septiembre;
	
	$cantidades_septiembre = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '09' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_septiembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_septiembre = pg_fetch_array($cantidades_septiembre)) {
		$total_cantidad_septiembre=floatval($total_c_septiembre["cantidad"]);
	}
	
	$retenciones_octubre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '10' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_octubre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_octubre=0;
	$total_cantidad_octubre=0;
	
	while ($total_r_octubre = pg_fetch_array($retenciones_octubre)) {
		$total_retenciones_octubre=floatval($total_r_octubre["monto"]);
		if($total_retenciones_octubre==""||$total_retenciones_octubre==" "||$total_retenciones_octubre==null){
			
		}
	}
	
	$total_retenido_octubre=$total_retenido_septiembre+$total_retenciones_octubre;
	
	$cantidades_octubre = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '10' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_octubre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_octubre = pg_fetch_array($cantidades_octubre)) {
		$total_cantidad_octubre=floatval($total_c_octubre["cantidad"]);
	}
	
	$retenciones_noviembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '11' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_noviembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_noviembre=0;
	$total_cantidad_noviembre=0;
	
	while ($total_r_noviembre = pg_fetch_array($retenciones_noviembre)) {
		$total_retenciones_noviembre=floatval($total_r_noviembre["monto"]);
		if($total_retenciones_noviembre==""||$total_retenciones_noviembre==" "||$total_retenciones_noviembre==null){
			
		}
	}
	
	$total_retenido_noviembre=$total_retenido_octubre+$total_retenciones_noviembre;
	
	$cantidades_noviembre = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '11' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_noviembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_noviembre = pg_fetch_array($cantidades_noviembre)) {
		$total_cantidad_noviembre=floatval($total_c_noviembre["cantidad"]);
	}
	
	$retenciones_diciembre = pg_query($conexion, "select sum(monto) as monto from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '12' and extract(Year from fecnom) = '$año') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539');");
	if (!$retenciones_diciembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$total_retenciones_diciembre=0;
	$total_cantidad_diciembre=0;
	
	while ($total_r_diciembre = pg_fetch_array($retenciones_diciembre)) {
		$total_retenciones_diciembre=floatval($total_r_diciembre["monto"]);
		if($total_retenciones_diciembre==""||$total_retenciones_diciembre==" "||$total_retenciones_diciembre==null){
			
		}
	}
	
	$total_retenido_diciembre=$total_retenido_noviembre+$total_retenciones_diciembre;
	
	$cantidades_diciembre = pg_query($conexion, "select cantidad from \"$esquema\".nphiscon WHERE (cedemp = '$cedula_empleado' AND cuenta_banco = '$numero_cuenta' and extract(Month from fecnom) = '12' and extract(Year from fecnom) = '$año' and cantidad <> '0') and (codcon = '506' or codcon = '526' or codcon = '535' or codcon = '539') GROUP BY cantidad;");
	if (!$cantidades_diciembre) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	while ($total_c_noviembre = pg_fetch_array($cantidades_diciembre)) {
		$total_cantidad_diciembre=floatval($total_c_noviembre["cantidad"]);
	}
	
	$contador_deducciones = 1;
	$nombre_deduccion[255]=array("");
	$monto_deduccion[255]=array("");
	
require('../fpdf17/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetMargins(10,20,10);
$pdf->Image('../imagenes/header_gobierno_recibo.png',10,10,195,12,'PNG');
$pdf->Cell(195,12,'',0,1,'C');
$pdf->SetFont('Arial','B',10);

$pdf->Cell(195,4,utf8_decode(''),0,1,'C');
$pdf->Cell(195,4,utf8_decode('COMPROBANTE DE RETENCIÓN'),0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,4,utf8_decode(''),0,1,'C');
$pdf->Cell(195,4,utf8_decode('DE IMPUESTO SOBRE LA RENTA ANUAL O DE CESE DE ACTIVIDADES PARA PERSONAS'),0,1,'C');
$pdf->Cell(195,4,utf8_decode('RESIDENTES PERCEPTORAS DE SUELDO, SALARIOS Y DEMÁS REMUNERACIONES SIMILARES'),0,1,'C');
$pdf->Cell(195,4,utf8_decode(''),0,1,'C');
$pdf->Cell(98,6,utf8_decode('BENEFICIARIO DE RETENCIÓN'),0,0,'L');
$pdf->Cell(15,6,utf8_decode('PERÍODO:'),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(82,6,'01-01-'.$año." hasta 31-12-".$año,0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,6,'Persona natural, Apellidos y nombres:',0,0,'L');
$pdf->Cell(98,6,utf8_decode('Cédula de identidad:'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,6,$nombre_empleado,1,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,6,$cedula_empleado,1,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,6,utf8_decode('TIPO DE AGENTE DE RETENCIÓN'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,6,utf8_decode('Persona Jurídica'),0,0,'L');
$pdf->Cell(49,6,utf8_decode('Nro. de R.I.F.'),0,0,'L');
$pdf->Cell(48,6,utf8_decode('Nro. de contribuyente'),0,1,'L');
$pdf->Cell(98,6,utf8_decode('FUNDACIÓN FONDO NACIONAL DE TRANSPORTE URBANO "FONTUR"'),1,0,'L');
$pdf->Cell(49,6,utf8_decode('G-20006289-4'),1,0,'L');
$pdf->Cell(48,6,utf8_decode($cedula_empleado),1,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,6,utf8_decode('DIRECCIÓN DEL AGENTE DE RETENCIÓN'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(195,6,'Av. Los Jabillos. Edf. FONTUR, Sabana Grande. Caracas - Venezuela',1,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,2,utf8_decode(''),0,1,'C');
$pdf->SetFont('Arial','',7);
$pdf->Cell(32,16,'MESES',1,0,'C');
$pdf->MultiCell(33,16,'',1,'C');
$pdf->SetXY(42,96);
$pdf->MultiCell(33,4,'REMUNERACIONES PAGADAS O ABONADAS EN CUENTAS',0,'C');
$pdf->SetXY(75,96);
$pdf->MultiCell(32,16,'',1,'C');
$pdf->SetXY(75,96);
$pdf->MultiCell(32,4,utf8_decode('PORCENTAJE DE RETENCIÓN'),0,'C');
$pdf->SetXY(107,96);
$pdf->MultiCell(33,16,'',1,'C');
$pdf->SetXY(107,96);
$pdf->MultiCell(33,4,'IMPUESTO RETENIDO',0,'C');
$pdf->SetXY(140,96);
$pdf->MultiCell(33,16,'',1,'C');
$pdf->SetXY(140,96);
$pdf->MultiCell(33,4,'REMUNERACIONES PAGADAS O ABONADAS EN CUENTAS ACUMULADAS',0,'C');
$pdf->SetXY(173,96);
$pdf->MultiCell(32,16,'',1,'C');
$pdf->SetXY(173,96);
$pdf->MultiCell(32,4,'IMPUESTO RETENIDO ACUMULADO',0,'C');
$pdf->SetXY(10,112);
$pdf->SetFont('Arial','',8);
$pdf->SetFont('Arial','',8);

$pdf->Cell(32,6,'ENERO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_enero,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_enero,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_enero,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_enero,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_enero,2,",","."),1,1,'R');

$pdf->Cell(32,6,'FEBRERO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_febrero,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_febrero,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_febrero,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_febrero,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_febrero,2,",","."),1,1,'R');

$pdf->Cell(32,6,'MARZO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_marzo,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_marzo,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_marzo,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_marzo,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_marzo,2,",","."),1,1,'R');

$pdf->Cell(32,6,'ABRIL',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_abril,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_abril,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_abril,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_abril,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_abril,2,",","."),1,1,'R');

$pdf->Cell(32,6,'MAYO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_mayo,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_mayo,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_mayo,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_mayo,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_mayo,2,",","."),1,1,'R');

$pdf->Cell(32,6,'JUNIO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_junio,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_junio,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_junio,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_junio,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_junio,2,",","."),1,1,'R');

$pdf->Cell(32,6,'JULIO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_julio,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_julio,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_julio,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_julio,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_julio,2,",","."),1,1,'R');

$pdf->Cell(32,6,'AGOSTO',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_agosto,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_agosto,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_agosto,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_agosto,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_agosto,2,",","."),1,1,'R');

$pdf->Cell(32,6,'SEPTIEMBRE',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_septiembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_septiembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_septiembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_septiembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_septiembre,2,",","."),1,1,'R');

$pdf->Cell(32,6,'OCTUBRE',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_octubre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_octubre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_octubre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_octubre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_octubre,2,",","."),1,1,'R');

$pdf->Cell(32,6,'NOVIEMBRE',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_noviembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_noviembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_noviembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_noviembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_noviembre,2,",","."),1,1,'R');

$pdf->Cell(32,6,'DICIEMBRE',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignaciones_diciembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_cantidad_diciembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_retenciones_diciembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_diciembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_diciembre,2,",","."),1,1,'R');

$pdf->Cell(32,6,'TOTAL',1,0,'L');
$pdf->Cell(33,6,number_format($total_asignado_diciembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,'',1,0,'R');
$pdf->Cell(33,6,number_format($total_retenido_diciembre,2,",","."),1,0,'R');
$pdf->Cell(33,6,number_format($total_asignado_diciembre,2,",","."),1,0,'R');
$pdf->Cell(32,6,number_format($total_retenido_diciembre,2,",","."),1,1,'R');

$pdf->SetFont('Arial','',8);
$pdf->SetXY(10,22);
$pdf->Cell(195,30,'',0,1,'C');
$pdf->SetXY(10,52);
$pdf->Cell(195,12,'',0,1,'C');	
$pdf->SetXY(10,70);
$pdf->Cell(195,24,'',0,1,'C');
$pdf->SetXY(10,100);
$pdf->Cell(195,12,'',0,1,'C');
$pdf->Image('../imagenes/FirmaDigitalCarnet.png',67,210,75,45,'PNG');
$pdf->SetXY(10,235);
$pdf->Cell(195,5,'______________________________',0,1,'C');
$pdf->Cell(195,4,'Firma y Sello',0,1,'C');
$pdf->Cell(195,4,utf8_decode('Agente de Retención'),0,1,'C');
$pdf->Cell(195,4,utf8_decode('LIC. DESSIREE CABRERA'),0,1,'C');
$pdf->Cell(195,4,'V- 12.071.444',0,1,'C');

////////////////////////////////////
// FIN PAGINA 1 COMIENZO PAGINA 2 //
////////////////////////////////////

$pdf->AddPage('P','Letter');
$pdf->SetMargins(10,20,10);
$pdf->Image('../imagenes/header_gobierno_recibo.png',10,10,195,12,'PNG');
$pdf->Cell(195,12,'',0,1,'C');
$pdf->SetFont('Arial','B',10);

$pdf->Cell(195,4,utf8_decode(''),0,1,'C');
$pdf->Cell(195,4,utf8_decode('DETALLE DE ASIGNACIONES Y DEDUCCIONES'),0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,4,utf8_decode(''),0,1,'C');
$pdf->Cell(98,4,utf8_decode('BENEFICIARIO DE RETENCIÓN'),0,0,'L');
$pdf->Cell(15,4,utf8_decode('PERÍODO:'),0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(82,4,'01-01-'.$año." hasta 31-12-".$año,0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,4,'Persona natural, Apellidos y nombres:',0,0,'L');
$pdf->Cell(98,4,utf8_decode('Cédula de identidad:'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,4,$nombre_empleado,1,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,4,$cedula_empleado,1,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,4,utf8_decode('TIPO DE AGENTE DE RETENCIÓN'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(98,4,utf8_decode('Persona Jurídica'),0,0,'L');
$pdf->Cell(49,4,utf8_decode('Nro. de R.I.F.'),0,0,'L');
$pdf->Cell(48,4,utf8_decode('Nro. de contribuyente'),0,1,'L');
$pdf->Cell(98,4,utf8_decode('FUNDACIÓN FONDO NACIONAL DE TRANSPORTE URBANO "FONTUR"'),1,0,'L');
$pdf->Cell(49,4,utf8_decode('G-20006289-4'),1,0,'L');
$pdf->Cell(48,4,utf8_decode($cedula_empleado),1,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(195,4,utf8_decode('DIRECCIÓN DEL AGENTE DE RETENCIÓN'),0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(195,4,'Av. Los Jabillos. Edf. FONTUR, Sabana Grande. Caracas - Venezuela',1,1,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(195,1,utf8_decode(''),0,1,'C');
$pdf->Cell(97,4,'CONCEPTO',1,0,'L');
$pdf->Cell(33,4,'ASIGNACIONES',1,0,'L');
$pdf->Cell(32,4,'DEDUCCIONES',1,0,'L');
$pdf->Cell(33,4,'TOTAL',1,1,'R');

$arc_asi = pg_query($conexion,"SELECT codcon, nomcon, sum(monto) FROM \"$esquema\".nphiscon WHERE cedemp = '$cedula_empleado' AND asided = 'A' AND fecnom BETWEEN '01-01-$año' AND '31-12-$año' GROUP BY codcon, nomcon ORDER BY codcon ASC");
while ($fila = pg_fetch_row($arc_asi)) {
	$pdf->Cell(97,3,utf8_decode($fila[1]),1,0,'L');
	$pdf->Cell(33,3,number_format($fila[2],2,",","."),1,0,'R');
	$pdf->Cell(32,3,'0,00',1,0,'R');
	$pdf->Cell(33,3,number_format($fila[2],2,",","."),1,1,'R');
}

$arc_ded = pg_query($conexion,"SELECT codcon, nomcon, sum(monto) FROM \"$esquema\".nphiscon WHERE cedemp = '$cedula_empleado' AND asided = 'D' AND fecnom BETWEEN '01-01-$año' AND '31-12-$año' GROUP BY codcon, nomcon ORDER BY codcon ASC");
while ($fila = pg_fetch_row($arc_ded)) {
	$pdf->Cell(97,3,utf8_decode($fila[1]),1,0,'L');
	$pdf->Cell(33,3,'0,00',1,0,'R');
	$pdf->Cell(32,3,"-".number_format($fila[2],2,",","."),1,0,'R');
	$pdf->Cell(33,3,"-".number_format($fila[2],2,",","."),1,1,'R');
}

$arc_totasi = pg_query($conexion,"SELECT sum(monto) FROM \"$esquema\".nphiscon WHERE cedemp = '$cedula_empleado' AND asided = 'A' AND fecnom BETWEEN '01-01-$año' AND '31-12-$año'");
while ($fila = pg_fetch_row($arc_totasi)) {
	$totasi = $fila[0];
}

$arc_totded = pg_query($conexion,"SELECT sum(monto) FROM \"$esquema\".nphiscon WHERE cedemp = '$cedula_empleado' AND asided = 'D' AND fecnom BETWEEN '01-01-$año' AND '31-12-$año'");
while ($fila = pg_fetch_row($arc_totded)) {
	$totded = $fila[0];
}

$tottot = $totasi-$totded;

$pdf->Cell(97,4,'TOTAL',1,0,'L');
$pdf->Cell(33,4,number_format($totasi,2,",","."),1,0,'R');
$pdf->Cell(32,4,number_format($totded,2,",","."),1,0,'R');
$pdf->Cell(33,4,number_format($tottot,2,",","."),1,1,'R');

$pdf->SetXY(10,100);
$pdf->Cell(195,12,'',0,1,'C');
$pdf->Image('../imagenes/FirmaDigitalCarnet.png',67,210,75,45,'PNG');
$pdf->SetXY(10,235);
$pdf->Cell(195,5,'______________________________',0,1,'C');
$pdf->Cell(195,4,'Firma y Sello',0,1,'C');
$pdf->Cell(195,4,utf8_decode('Agente de Retención'),0,1,'C');
$pdf->Cell(195,4,utf8_decode('LIC. DESSIREE CABRERA'),0,1,'C');
$pdf->Cell(195,4,'V- 12.071.444',0,1,'C');

$pdf->Output();
?>
</body>
</html>