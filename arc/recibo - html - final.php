<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<style>
		html,body{width:100%;height:100%;margin:0px;}
		table{width:auto;height:auto;border:2px solid black;border-collapse:collapse;}
		td{text-align:center;}
		td.bordeado{border:2px solid black;}
		td.borde_arriba{border-top:2px solid black;}
		td.borde_abajo{border-bottom:2px solid black;}
		p{font:12px Arial;margin:0px;}
		p.izquierda{text-align:left;}
		p.derecha{text-align:right;}
		p.pie{font-size:9px;}
	</style>
</head>
<body>
<?php
	//session_start();
	//require('../incluidos/constantes.php');
	if(isset($_POST['cuenta'])&&isset($_POST['ci'])&&isset($_POST['año_quincena'])&&isset($_POST['mes_quincena'])&&isset($_POST['quincena'])&&isset($_POST['captcha_code'])){
	include '../incluidos/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=cvi">';
			return 0;
		}
		if($_POST['quincena']=='1'){
			$dia_quincena_1='01';
			$dia_quincena_2='15';
		}else{
			$dia_quincena_1='16';
			$mes = mktime( 0, 0, 0, $_POST['mes_quincena'], 1, $_POST['año_quincena'] );
			$dia_quincena_2 = date('t',$mes);
		}
		$fecha_inicial=$_POST['año_quincena']."-".$_POST['mes_quincena']."-".$dia_quincena_1;
		$fecha_final=$_POST['año_quincena']."-".$_POST['mes_quincena']."-".$dia_quincena_2;
		$numero_cuenta=$_POST['cuenta'];
		$cedula=$_POST['ci'];
		$total_asignaciones = 0;
		$total_deducciones = 0;
		//include('pgsql-consulta_recibo.php');
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

	$consulta = pg_query($conexion, "SELECT * FROM \"SIMA014\".nphiscon WHERE cedemp = '$cedula' AND cuenta_banco = '$numero_cuenta' AND fecnom BETWEEN '$fecha_inicial' AND '$fecha_final' LIMIT 1;");
	if (!$consulta) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	if(pg_num_rows($consulta)==0){
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=rne">'; exit;
	}

	while ($fila = pg_fetch_array($consulta)) {
		$codigo_nomina = $fila['codnom'];
		$cedula_empleado = $fila['cedemp'];
		
		/*echo "<p>fecnom: $fila[fecnom]</p>";*/ $fecha_nomina = $fila['fecnom'];
		/*echo "<p>nomemp: $fila[nomemp]</p>";*/ $nombre_empleado = $fila['nomemp'];
		/*echo "<p>cedemp: $cedula_empleado</p>";*/
		
		$consulta1 = pg_query($conexion, "SELECT fecing FROM \"SIMA014\".nphojint WHERE cedemp = '$cedula_empleado';");
		if (!$consulta1) {
			//echo "No se pudo ejecutar la consulta."; exit;
		}

		while ($fila1 = pg_fetch_array($consulta1)) {
			/*echo "<p>fecing: $fila1[fecing]</p>";*/ $fecha_ingreso = $fila1['fecing'];
		}
		
		$consulta2 = pg_query($conexion, "SELECT nomnom FROM \"SIMA014\".npnomina WHERE codnom = '$codigo_nomina';");
		if (!$consulta2) {
			//echo "No se pudo ejecutar la consulta."; exit;
		}

		while ($fila2 = pg_fetch_array($consulta2)) {
			/*echo "<p>nomnom: $fila2[nomnom]</p>";*/ $nombre_nomina = $fila2['nomnom'];
		}
		
		/*echo "<p>desniv: $fila[desniv]</p>";*/ $dependencia = $fila['desniv'];
		/*echo "<p>nomcar: $fila[nomcar]</p>";*/ $nombre_cargo = $fila['nomcar'];
		/*echo "<p>cuenta_banco: $fila[cuenta_banco]</p>";*/ $cuenta_banco = $fila['cuenta_banco'];
		
		$consulta3 = pg_query($conexion, "SELECT monto FROM \"SIMA014\".nphiscon WHERE cedemp = '$cedula' AND cuenta_banco = '$numero_cuenta' AND nomcon = 'SUELDO BASICO QUINCENAL' AND fecnom BETWEEN '$fecha_inicial' AND '$fecha_final';");
		if (!$consulta3) {
			//echo "No se pudo ejecutar la consulta."; exit;
		}

		while ($fila3 = pg_fetch_array($consulta3)) {
			$sueldo_basico_mensual = $fila3['monto']*2;
			//echo "<p>monto: $sueldo_basico_mensual</p>";
		}
	}
	
	$consulta_asignaciones = pg_query($conexion, "SELECT nomcon, monto FROM \"SIMA014\".nphiscon WHERE cedemp = '$cedula' AND cuenta_banco = '$numero_cuenta' AND ASIDED = 'A' AND fecnom BETWEEN '$fecha_inicial' AND '$fecha_final';");
	if (!$consulta_asignaciones) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$contador_asignaciones = 1;
	$nombre_asignacion[255]=array("");
	$monto_asignacion[255]=array("");
	
	while ($fila_asignaciones = pg_fetch_array($consulta_asignaciones)) {
		//echo "<p>$fila_asignaciones[nomcon]: $fila_asignaciones[monto]</p>";
		//$pdf->Cell(170,6,$fila_asignaciones['nomcon'],1,0,'C');
		//$pdf->Cell(25,6,number_format($fila_asignaciones['monto'],2,",","."),1,1,'C');
		$nombre_asignacion[$contador_asignaciones]=$fila_asignaciones['nomcon'];
		$monto_asignacion[$contador_asignaciones]=$fila_asignaciones['monto'];
		$contador_asignaciones++;
		$total_asignaciones += $fila_asignaciones['monto'];
	}
	
	//echo "<p>total_asignaciones: $total_asignaciones</p>";
	
	$consulta_deducciones = pg_query($conexion, "SELECT nomcon, monto FROM \"SIMA014\".nphiscon WHERE cedemp = '$cedula' AND cuenta_banco = '$numero_cuenta' AND ASIDED = 'D' AND fecnom BETWEEN '$fecha_inicial' AND '$fecha_final';");
	if (!$consulta_deducciones) {
		//echo "No se pudo ejecutar la consulta."; exit;
	}
	
	$contador_deducciones = 1;
	$nombre_deduccion[255]=array("");
	$monto_deduccion[255]=array("");
	
	while ($fila_deducciones = pg_fetch_array($consulta_deducciones)) {
		//echo "<p>$fila_deducciones[nomcon]: $fila_deducciones[monto]</p>";
		//$pdf->Cell(170,6,$fila_deducciones['nomcon'],1,0,'C');
		//$pdf->Cell(25,6,number_format($fila_deducciones['monto'],2,",","."),1,1,'C');
		$nombre_deduccion[$contador_deducciones]=$fila_deducciones['nomcon'];
		$monto_deduccion[$contador_deducciones]=$fila_deducciones['monto'];
		$contador_deducciones++;
		$total_deducciones += $fila_deducciones['monto'];
	}
	
	$cuenta_contador = 0;
	if($contador_asignaciones>$contador_deducciones){
		$cuenta_contador=$contador_asignaciones;
	}else{
		$cuenta_contador=$contador_deducciones;
	}
	
	//echo "<p>total_deducciones: $total_deducciones</p>";
	
	$total_cobrar=$total_asignaciones-$total_deducciones;
	
	//echo "<p>total_cobrar: $total_cobrar</p>";
	
?>
	<center><table>
		<tr>
			<td>
				<img src="../imagenes/header_gobierno.png" width="900px" />
				<center><table>
					<tr>
						<td style="width:898px;">
							<p><b>RECIBO DE PAGO</b></p>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td colspan="4" style="width:898px;">
							<center><table>
								<tr>
									<td style="width:896px;">
										<p><b>Gerencia de Recursos Humanos</b></p>
										<p class="izquierda"><b>Período de Pago:</b> Desde <?php echo arregla_fecha($fecha_inicial); ?> Hasta <?php echo arregla_fecha($fecha_final); ?></p>
									</td>
								</tr>
							</table></center>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<center><table>
								<tr>
									<td style="width:896px;">
										<p><b>Datos del Trabajador</b></p>
									</td>
								</tr>
							</table></center>
						</td>
					</tr>
					<tr>
						<td>
							<p class="izquierda"><b>Trabajador:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $nombre_empleado; ?></p>
						</td>
						<td>
							<p class="izquierda"><b>N° de Cédula de Identidad:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $cedula_empleado; ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<p class="izquierda"><b>Fecha de Ingreso:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo arregla_fecha($fecha_ingreso); ?></p>
						</td>
						<td>
							<p class="izquierda"><b>Tipo de Nómina:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $nombre_nomina; ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<p class="izquierda"><b>Dependencia:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $dependencia; ?></p>
						</td>
						<td>
							<p class="izquierda"><b>Cargo:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $nombre_cargo; ?></p>
						</td>
					</tr>
					<tr>
						<td>
							<p class="izquierda"><b>Nro de Cuenta:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo $numero_cuenta; ?></p>
						</td>
						<td>
							<p class="izquierda"><b>Sueldo Básico Mensual:</b></p>
						</td>
						<td>
							<p class="izquierda"><?php echo number_format($sueldo_basico_mensual,2,",","."); ?></p>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<center><table>
								<tr>
									<td style="width:896px;">
										<p><b>Descripción</b></p>
									</td>
								</tr>
							</table></center>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<center><table>
								<tr>
									<td style="width:225px;">
										<p><b>Asignaciones</b></p>
									</td>
									<td style="width:225px;">
										<p><b>Monto (Bs.)</b></p>
									</td>
									<td style="width:225px;">
										<p><b>Deducciones</b></p>
									</td>
									<td style="width:225px;">
										<p><b>Monto (Bs.)</b></p>
									</td>
								</tr>
								<?php
									$su=0;
									while($su<=$cuenta_contador){
										echo "<tr>";
										if(isset($nombre_asignacion[$su])){
											echo '<td style="width:225px;">';
											echo '<p class="izquierda">'.$nombre_asignacion[$su].'</p>';
											echo '</td><td style="width:225px;">';
											echo '<p>'.$monto_asignacion[$su].'</p>';
										}else{
											echo '<td style="width:225px;">';
											echo '</td><td style="width:225px;">';
										}
										if(isset($nombre_deduccion[$su])){
											echo '<td style="width:225px;">';
											echo '<p class="izquierda">'.$nombre_deduccion[$su].'</p>';
											echo '</td><td style="width:225px;">';
											echo '<p>'.$monto_deduccion[$su].'</p>';
										}else{
											echo '<td style="width:225px;">';
											echo '</td><td style="width:225px;">';
										}
										echo "</tr>";
										$su++;
									}
								?>
								<tr>
									<td class="borde_arriba" style="width:225px;">
										<p class="derecha"><b>Total Asignaciones:</b></p>
									</td>
									<td class="borde_arriba" style="width:225px;">
										<p><b><?php echo $total_asignaciones; ?></b></p>
									</td>
									<td class="borde_arriba" style="width:225px;">
										<p class="derecha"><b>Total Deducciones:</b></p>
									</td>
									<td class="borde_arriba" style="width:225px;">
										<p><b><?php echo $total_deducciones; ?></b></p>
									</td>
								</tr>
								<tr>
									<td class="borde_abajo" style="width:225px;">
										
									</td>
									<td class="borde_abajo" style="width:225px;">
										
									</td>
									<td class="borde_abajo" style="width:225px;">
										<p class="derecha"><b>Total a Cobrar:</b></p>
									</td>
									<td class="borde_abajo" style="width:225px;">
										<p><b><?php echo $total_cobrar; ?></b></p>
									</td>
								</tr>
							</table></center>
						</td>
					</tr>
				</table></center>
			</td>
		</tr>
		<tr>
			<td class="bordeado">
				<p class="pie">*Independencia, Patria Socialista, Viviremos y Venceremos*</p>
			</td>
		</tr>
	</table></center>
	<center><a href='javascript:window.print(); void 0;'><img src="../imagenes/imprimir.png" /></a></center>
<?php
	//echo '<!--<META HTTP-EQUIV="Refresh" Content="0; URL=pgsql-insercion_recibo.php">-->';
?>
</body>
</html>