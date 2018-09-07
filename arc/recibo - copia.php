<?php
	session_start();
?>
<html>
<head>
	<meta charset='utf-8' />
</head>
<body>
<?
	require('../incluidos/constantes.php');
	if(isset($_POST['cuenta'])&&isset($_POST['ci'])&&isset($_POST['quincena'])&&isset($_POST['captcha_code'])){
	include '../incluidos/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=cvi">';
			return 0;
		}
		$quincena=$_POST['quincena'];
		switch($quincena){
			case 1: $_SESSION['fecha1']='01/01/'.date('Y'); $_SESSION['fecha2']='15/01/'.date('Y'); break;
			case 2: $_SESSION['fecha1']='16/01/'.date('Y'); $_SESSION['fecha2']='31/01/'.date('Y'); break;
			case 3: $_SESSION['fecha1']='01/02/'.date('Y'); $_SESSION['fecha2']='15/02/'.date('Y'); break;
			case 4: $_SESSION['fecha1']='16/02/'.date('Y'); $_SESSION['fecha2']=$_POST['bisiesto'].'/02/'.date('Y'); break;
			case 5: $_SESSION['fecha1']='01/03/'.date('Y'); $_SESSION['fecha2']='15/03/'.date('Y'); break;
			case 6: $_SESSION['fecha1']='16/03/'.date('Y'); $_SESSION['fecha2']='31/03/'.date('Y'); break;
			case 7: $_SESSION['fecha1']='01/04/'.date('Y'); $_SESSION['fecha2']='15/04/'.date('Y'); break;
			case 8: $_SESSION['fecha1']='16/04/'.date('Y'); $_SESSION['fecha2']='30/04/'.date('Y'); break;
			case 9: $_SESSION['fecha1']='01/05/'.date('Y'); $_SESSION['fecha2']='15/05/'.date('Y'); break;
			case 10: $_SESSION['fecha1']='16/05/'.date('Y'); $_SESSION['fecha2']='31/05/'.date('Y'); break;
			case 11: $_SESSION['fecha1']='01/06/'.date('Y'); $_SESSION['fecha2']='15/06/'.date('Y'); break;
			case 12: $_SESSION['fecha1']='16/06/'.date('Y'); $_SESSION['fecha2']='30/06/'.date('Y'); break;
			case 13: $_SESSION['fecha1']='01/07/'.date('Y'); $_SESSION['fecha2']='15/07/'.date('Y'); break;
			case 14: $_SESSION['fecha1']='16/07/'.date('Y'); $_SESSION['fecha2']='31/07/'.date('Y'); break;
			case 15: $_SESSION['fecha1']='01/08/'.date('Y'); $_SESSION['fecha2']='15/08/'.date('Y'); break;
			case 16: $_SESSION['fecha1']='16/08'.date('Y'); $_SESSION['fecha2']='31/08/'.date('Y'); break;
			case 17: $_SESSION['fecha1']='01/09/'.date('Y'); $_SESSION['fecha2']='15/09/'.date('Y'); break;
			case 18: $_SESSION['fecha1']='16/09/'.date('Y'); $_SESSION['fecha2']='30/09/'.date('Y'); break;
			case 19: $_SESSION['fecha1']='01/10/'.date('Y'); $_SESSION['fecha2']='15/10/'.date('Y'); break;
			case 20: $_SESSION['fecha1']='16/10/'.date('Y'); $_SESSION['fecha2']='31/10/'.date('Y'); break;
			case 21: $_SESSION['fecha1']='01/11/'.date('Y'); $_SESSION['fecha2']='15/11/'.date('Y'); break;
			case 22: $_SESSION['fecha1']='16/11/'.date('Y'); $_SESSION['fecha2']='30/11/'.date('Y'); break;
			case 23: $_SESSION['fecha1']='01/12/'.date('Y'); $_SESSION['fecha2']='15/12/'.date('Y'); break;
			case 24: $_SESSION['fecha1']='16/12/'.date('Y'); $_SESSION['fecha2']='31/12'.date('Y'); break;
		}
		$cuenta="0".$_POST['cuenta'];
		$ci=$_POST['ci'];
		include('pgsql-consulta_recibo.php');
		$añoanterior = date('Y')-1;
	}else{
		header('Location: ingreso.php');
	}
	
	if(pre_consulta_pgsql('accion_centralizada'."_".$año."_".$quincena, $ci, $cuenta)!=0){
		$nomina='accion_centralizada'."_".$año."_".$quincena;
		$tabla='accion_centralizada'."_".$año."_".$quincena;
	}else if(pre_consulta_pgsql('accion_centralizada'."_".$año."_".$quincena, number_format($ci,0,"","."), $cuenta)!=0){
		$nomina='accion_centralizada'."_".$año."_".$quincena;
		$tabla='accion_centralizada'."_".$año."_".$quincena;
		$ci=number_format($ci,0,"",".");   	
	}else if(pre_consulta_pgsql('fijos'."_".$año."_".$quincena, $ci, $cuenta)!=0){
		$nomina='fijos'."_".$año."_".$quincena;
		$tabla='fijos'."_".$año."_".$quincena;
	}else if(pre_consulta_pgsql('fijos'."_".$año."_".$quincena, number_format($ci,0,"","."), $cuenta)!=0){
		$nomina='fijos'."_".$año."_".$quincena;
		$tabla='fijos'."_".$año."_".$quincena;
		$ci=number_format($ci,0,"",".");	
	}else if(pre_consulta_pgsql('nuevo_circo'."_".$año."_".$quincena, $ci, $cuenta)!=0){
		$nomina='nuevo_circo'."_".$año."_".$quincena;
		$tabla='nuevo_circo'."_".$año."_".$quincena;
	}else if(pre_consulta_pgsql('nuevo_circo'."_".$año."_".$quincena, number_format($ci,0,"","."), $cuenta)!=0){
		$nomina='nuevo_circo'."_".$año."_".$quincena;
		$tabla='nuevo_circo'."_".$año."_".$quincena;
		$ci=number_format($ci,0,"",".");	
	}else if(pre_consulta_pgsql('pasaje_estudiantil'."_".$año."_".$quincena, $ci, $cuenta)!=0){
		$nomina='pasaje_estudiantil'."_".$año."_".$quincena;
		$tabla='pasaje_estudiantil'."_".$año."_".$quincena;
	}else if(pre_consulta_pgsql('pasaje_estudiantil'."_".$año."_".$quincena, number_format($ci,0,"","."), $cuenta)!=0){
		$nomina='pasaje_estudiantil'."_".$año."_".$quincena;
		$tabla='pasaje_estudiantil'."_".$año."_".$quincena;
		$ci=number_format($ci,0,"",".");	
	}else if(pre_consulta_pgsql('peaje'."_".$año."_".$quincena, $ci, $cuenta)!=0){
		$nomina='peaje'."_".$año."_".$quincena;
		$tabla='peaje'."_".$año."_".$quincena;
	}else if(pre_consulta_pgsql('peaje'."_".$año."_".$quincena, number_format($ci,0,"","."), $cuenta)!=0){
		$nomina='peaje'."_".$año."_".$quincena;
		$tabla='peaje'."_".$año."_".$quincena;
		$ci=number_format($ci,0,"",".");	
	}else{
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=ingreso.php?error=rne">';
	}
	$_SESSION['correlativo1']=$correlativo1;
	$_SESSION['lugar']=$lugar; // CONSTANTE
	$_SESSION['fechaahora']=$fechaahora; // CONSTANTE
	$_SESSION['destino']=$destino;
	$_SESSION['institucion']=$institucion; // CONSTANTE
	$_SESSION['nacgerente']=$nacgerente; // CONSTANTE
	$_SESSION['ci']=$ci;
	//$_SESSION['bonoalimentacion']=$bonoalimentacion; // CONSTANTE
	$_SESSION['nyagerente']=$nyagerente; // CONSTANTE
	$_SESSION['lema']=$lema; // CONSTANTE
	$_SESSION['correlativo2']=$correlativo2; // CONSTANTE
	$_SESSION['rif']=$rif; // CONSTANTE
	$_SESSION['institucion2']=$institucion2; // CONSTANTE
	$_SESSION['direccioninstitucion']=$direccioninstitucion; // CONSTANTE
	$_SESSION['contacto']=$contacto; // CONSTANTE
	$_SESSION['hash']=$hash;
	$_SESSION['nomina']=$nomina;
	$_SESSION['apellidosynombres']=consulta_pgsql("APELLIDOS Y NOMBRES", $tabla, $ci, $cuenta);
	$_SESSION['fechaingreso']=consulta_pgsql("FECHA DE INGRESO", $tabla, $ci, $cuenta);
	$_SESSION['cargo']=consulta_pgsql("DENOMINACION DEL CARGO", $tabla, $ci, $cuenta);
	$_SESSION['gerencia']=strtoupper(consulta_pgsql("GERENCIA", $tabla, $ci, $cuenta));
	$_SESSION['sueldobase']=consulta_pgsql("SUELDO BASICO QUINCENAL", $tabla, $ci, $cuenta);
	if(valida_columna($tabla,"DIFERENCIA DE SUELDO POR ENCARGADURIA QUINCENAL")!=0){
		$_SESSION['diferenciaquincenal']=consulta_pgsql("DIFERENCIA DE SUELDO POR ENCARGADURIA QUINCENAL", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA JERARQUIA QUINCENAL")!=0){
		$_SESSION['primajerarquia']=consulta_pgsql("PRIMA JERARQUIA QUINCENAL", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA ANTIGUEDAD  QUINCENAL")!=0){
		$_SESSION['primaantiguedad']=consulta_pgsql("PRIMA ANTIGUEDAD  QUINCENAL", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA DE PROFESIONALIZACION QUINCENAL")!=0){
		$_SESSION['primaprofesional']=consulta_pgsql("PRIMA DE PROFESIONALIZACION QUINCENAL", $tabla, $ci, $cuenta);
	}
	$_SESSION['primahogar']=consulta_pgsql("PRIMA HOGAR QUINCENA", $tabla, $ci, $cuenta);
	if(valida_columna($tabla,"BONO DE PRODUCTIVIDAD ".date('Y'))!=0){
		$_SESSION['bonoproductividad']=consulta_pgsql("BONO DE PRODUCTIVIDAD ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO DE FIN DE AÑO ".$añoanterior)!=0){
		$_SESSION['bonofindeaño']=consulta_pgsql("BONO DE FIN DE AÑO ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO UNICO ".$añoanterior)!=0){
		$_SESSION['bonounico']=consulta_pgsql("BONO UNICO ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO DE EVALUACION AÑO ".date('Y'))!=0){
		$_SESSION['bonoevaluacion']=consulta_pgsql("BONO DE EVALUACION AÑO ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"DIFERENCIA DE SUELDO PENDIENTE")!=0){
		$_SESSION['diferenciadesueldo']=consulta_pgsql("DIFERENCIA DE SUELDO PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE")!=0){
		$_SESSION['diferenciaencargaduria']=consulta_pgsql("DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA ANTIGUEDAD PENDIENTE")!=0){
		$_SESSION['primaantiguedadpendiente']=consulta_pgsql("PRIMA ANTIGUEDAD PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA JERARQUIA PENDIENTE")!=0){
		$_SESSION['primajerarquiapendiente']=consulta_pgsql("PRIMA JERARQUIA PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA DE PROFESIONALIZACION PENDIENTE")!=0){
		$_SESSION['primaprofesionalpendiente']=consulta_pgsql("PRIMA DE PROFESIONALIZACION PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA HOGAR PENDIENTE")!=0){
		$_SESSION['primahogarpendiente']=consulta_pgsql("PRIMA HOGAR PENDIENTE", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO DE PRODUCTIVIDAD PENDIENTE ".date('Y'))!=0){
		$_SESSION['primaproductividadpendiente']=consulta_pgsql("BONO DE PRODUCTIVIDAD PENDIENTE ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"DIFERENCIA DE SUELDO PENDIENTE ".$añoanterior)!=0){
		$_SESSION['diferenciadesueldopendiente']=consulta_pgsql("DIFERENCIA DE SUELDO PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE ".$añoanterior)!=0){
		$_SESSION['diferenciasueldoencargaduriapendiente']=consulta_pgsql("DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA ANTIGUEDAD PENDIENTE ".$añoanterior)!=0){
		$_SESSION['primaantiguedadpendienteanterior']=consulta_pgsql("PRIMA ANTIGUEDAD PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA JERARQUIA PENDIENTE ".$añoanterior)!=0){
		$_SESSION['primajerarquiapendientependienteanterior']=consulta_pgsql("PRIMA JERARQUIA PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA DE PROFESIONALIZACION PENDIENTE ".$añoanterior)!=0){
		$_SESSION['primaprofesionalpendientependienteanterior']=consulta_pgsql("PRIMA DE PROFESIONALIZACION PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"PRIMA HOGAR PENDIENTE ".$añoanterior)!=0){
		$_SESSION['primahogarpendientependienteanterior']=consulta_pgsql("PRIMA HOGAR PENDIENTE ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO VACACIONAL (CAUSADO)")!=0){
		$_SESSION['bonovacacional']=consulta_pgsql("BONO VACACIONAL (CAUSADO)", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BONO VACACIONAL CORRESPONDIENTE AL AÑO ".$añoanterior)!=0){
		$_SESSION['bonovacacionalanterior']=consulta_pgsql("BONO VACACIONAL CORRESPONDIENTE AL AÑO ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BECA ESCOLAR TERCER PAGO ( PRIMER LAPSO) AÑO ESCOLAR ".date('Y'))!=0){
		$_SESSION['becaescolartercerpago']=consulta_pgsql("BECA ESCOLAR TERCER PAGO ( PRIMER LAPSO) AÑO ESCOLAR ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"GUARDERIA ".date('Y'))!=0){
		$_SESSION['guarderia']=consulta_pgsql("GUARDERIA ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"BECA ESCOLAR TERCER PAGO ( REZAGADOS ) AÑO ESCOLAR ".$añoanterior)!=0){
		$_SESSION['becaescolartercerpagorezagados']=consulta_pgsql("BECA ESCOLAR TERCER PAGO ( REZAGADOS ) AÑO ESCOLAR ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"DIFERENCIA DE INTERESES DE FIDEICOMISO ".$añoanterior)!=0){
		$_SESSION['diferenciainteresesfideicomiso']=consulta_pgsql("DIFERENCIA DE INTERESES DE FIDEICOMISO ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"MONTO DE DIAS ADICIONALES")!=0){
		$_SESSION['diasadicionales']=consulta_pgsql("MONTO DE DIAS ADICIONALES", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"SUBTOTAL DE ASIGNACIONES EN LA QUINCENA PARA DEDUCCION DE FAOV")!=0){
		$_SESSION['subtotalasignacionesfaov']=consulta_pgsql("SUBTOTAL DE ASIGNACIONES EN LA QUINCENA PARA DEDUCCION DE FAOV", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"Deducciones de S.S.O")!=0){
		$_SESSION['deduccionessso']=consulta_pgsql("Deducciones de S.S.O", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"Deducciones de P.I.E")!=0){
		$_SESSION['deduccionespie']=consulta_pgsql("Deducciones de P.I.E", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"Deducciones de FAOV")!=0){
		$_SESSION['deduccionesfaov']=consulta_pgsql("Deducciones de FAOV", $tabla, $ci, $cuenta);
	}	
	if(valida_columna($tabla,"ISLR")!=0){
		$_SESSION['islr']=consulta_pgsql("ISLR", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"Deduccion por Plan de Ahorro Quincenal")!=0){
		$_SESSION['plandeahorroquincenal']=consulta_pgsql("Deduccion por Plan de Ahorro Quincenal", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"Deducciones de F.J.P")!=0){
		$_SESSION['deduccionfjp']=consulta_pgsql("Deducciones de F.J.P", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"SINDICATO")!=0){
		$_SESSION['sindicato']=consulta_pgsql("SINDICATO", $tabla, $ci, $cuenta);
	}	
	if(valida_columna($tabla,"Deducción por Prestamo de Plan de Ahorro Quincenal")!=0){
		$_SESSION['deduccionprestamoplandeahorro']=consulta_pgsql("Deducción por Prestamo de Plan de Ahorro Quincenal", $tabla, $ci, $cuenta);
	}	
	if(valida_columna($tabla,"AJUSTE Deducción de S.S.O ".date('Y'))!=0){
		$_SESSION['ajustesso']=consulta_pgsql("AJUSTE Deducción de S.S.O ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deducción P.I.E. ".date('Y'))!=0){
		$_SESSION['ajustepie']=consulta_pgsql("AJUSTE Deducción P.I.E. ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deducción de FAOV ".date('Y'))!=0){
		$_SESSION['ajustefaov']=consulta_pgsql("AJUSTE Deducción de FAOV ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deduccion de F.J.P ".date('Y'))!=0){
		$_SESSION['ajustefjp']=consulta_pgsql("AJUSTE Deduccion de F.J.P ".date('Y'), $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deducción de S.S.O ".$añoanterior)!=0){
		$_SESSION['ajustessoanterior']=consulta_pgsql("AJUSTE Deducción de S.S.O ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deducción P.I.E. ".$añoanterior)!=0){
		$_SESSION['ajustepieanterior']=consulta_pgsql("AJUSTE Deducción P.I.E. ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deducción de FAOV ".$añoanterior)!=0){
		$_SESSION['ajustefaovanterior']=consulta_pgsql("AJUSTE Deducción de FAOV ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"AJUSTE Deduccion de F.J.P ".$añoanterior)!=0){
		$_SESSION['ajustefjpanterior']=consulta_pgsql("AJUSTE Deduccion de F.J.P ".$añoanterior, $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"RETENCION JUDICIAL QUINCENAL (Pensión de Alimentación)")!=0){
		$_SESSION['retencionjudicialquincenal']=consulta_pgsql("RETENCION JUDICIAL QUINCENAL (Pensión de Alimentación)", $tabla, $ci, $cuenta);
	}	
	if(valida_columna($tabla,"DESCUENTO POR DIA NO LABORADO")!=0){
		$_SESSION['descuentodianolaborado']=consulta_pgsql("DESCUENTO POR DIA NO LABORADO", $tabla, $ci, $cuenta);
	}
	if(valida_columna($tabla,"INAVI")!=0){
		$_SESSION['inavi']=consulta_pgsql("INAVI", $tabla, $ci, $cuenta);
	}
	$_SESSION['totalasignaciones']=consulta_pgsql("TOTAL ASIGNACIONES", $tabla, $ci, $cuenta);
	$_SESSION['totaldeducciones']=consulta_pgsql("TOTAL DEDUCCIONES", $tabla, $ci, $cuenta);
	$_SESSION['totalacobrar']=consulta_pgsql("TOTAL A COBRAR", $tabla, $ci, $cuenta);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pgsql-insercion_recibo.php">';
?>
</body>
</html>