<?php

	function generar_tablas_recibo($año){
		require('../incluidos/bd-pgsql-recibos.php');
		for($i=1;$i<=5;$i++){
			switch($i){
				case 1: $tabla='accion_centralizada'; break;
				case 2: $tabla='fijos'; break;
				case 3: $tabla='nuevo_circo'; break;
				case 4: $tabla='pasaje_estudiantil'; break;
				case 5: $tabla='peaje'; break;
			}
			for($j=1;$j<=24;$j++){
				$consulta='CREATE TABLE IF NOT EXISTS '.$tabla.'_'.$año.'_'.$j.'
					(
					  "APELLIDOS Y NOMBRES" character varying(250),
					  "GERENCIA" character varying(250),
					  "DIRECCION" character varying(250),
					  "CÉDULA DE IDENTIDAD" character varying(250),
					  "FECHA DE INGRESO" character varying(250),
					  "DENOMINACION DEL CARGO" character varying(250),
					  "SUELDO BASICO QUINCENAL" character varying(250),
					  "DIFERENCIA DE SUELDO POR ENCARGADURIA QUINCENAL" character varying(250),
					  "PRIMA ANTIGUEDAD  QUINCENAL" character varying(250),
					  "PRIMA JERARQUIA QUINCENAL" character varying(250),
					  "PRIMA DE PROFESIONALIZACION QUINCENAL" character varying(250),
					  "PRIMA HOGAR QUINCENA" character varying(250),
					  "BONO DE PRODUCTIVIDAD 2013" character varying(250),
					  "BONO DE FIN DE AÑO 2012" character varying(250),
					  "BONO UNICO 2012" character varying(250),
					  "BONO DE EVALUACION AÑO 2013" character varying(250),
					  "DIFERENCIA DE SUELDO PENDIENTE" character varying(250),
					  "DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE" character varying(250),
					  "PRIMA ANTIGUEDAD PENDIENTE" character varying(250),
					  "PRIMA JERARQUIA PENDIENTE" character varying(250),
					  "PRIMA DE PROFESIONALIZACION PENDIENTE" character varying(250),
					  "PRIMA HOGAR PENDIENTE" character varying(250),
					  "BONO DE PRODUCTIVIDAD PENDIENTE 2013" character varying(250),
					  "DIFERENCIA DE SUELDO PENDIENTE 2012" character varying(250),
					  "DIFERENCIA DE SUELDO POR ENCARGADURIA PENDIENTE 2012" character varying(250),
					  "PRIMA ANTIGUEDAD PENDIENTE 2012" character varying(250),
					  "PRIMA JERARQUIA PENDIENTE 2012" character varying(250),
					  "PRIMA DE PROFESIONALIZACION PENDIENTE 2012" character varying(250),
					  "PRIMA HOGAR PENDIENTE 2012" character varying(250),
					  "BONO VACACIONAL (CAUSADO)" character varying(250),
					  "BONO VACACIONAL CORRESPONDIENTE AL AÑO 2012" character varying(250),
					  "BECA ESCOLAR TERCER PAGO ( PRIMER LAPSO) AÑO ESCOLAR 2013" character varying(250),
					  "GUARDERIA 2013" character varying(250),
					  "BECA ESCOLAR TERCER PAGO ( REZAGADOS ) AÑO ESCOLAR 2012" character varying(250),
					  "DIFERENCIA DE INTERESES DE FIDEICOMISO 2012" character varying(250),
					  "MONTO DE DIAS ADICIONALES" character varying(250),
					  "SUBTOTAL DE ASIGNACIONES EN LA QUINCENA PARA DEDUCCION DE FAOV" character varying(250),
					  "Deducciones de S.S.O" character varying(250),
					  "Deducciones de P.I.E" character varying(250),
					  "Deducciones de FAOV" character varying(250),
					  "ISLR" character varying(250),
					  "Deduccion por Plan de Ahorro Quincenal" character varying(250),
					  "Deducciones de F.J.P" character varying(250),
					  "SINDICATO" character varying(250),
					  "Deducción por Prestamo de Plan de Ahorro Quincenal" character varying(250),
					  "AJUSTE Deducción de S.S.O 2013" character varying(250),
					  "AJUSTE Deducción P.I.E. 2013" character varying(250),
					  "AJUSTE Deducción de FAOV 2013" character varying(250),
					  "AJUSTE Deduccion de F.J.P 2013" character varying(250),
					  "AJUSTE Deducción de S.S.O 2012" character varying(250),
					  "AJUSTE Deducción P.I.E. 2012" character varying(250),
					  "AJUSTE Deducción de FAOV 2012" character varying(250),
					  "AJUSTE Deduccion de F.J.P 2012" character varying(250),
					  "RETENCION JUDICIAL QUINCENAL (Pensión de Alimentación)" character varying(250),
					  "DESCUENTO POR DIA NO LABORADO" character varying(250),
					  "INAVI" character varying(250),
					  "TOTAL ASIGNACIONES" character varying(250),
					  "TOTAL DEDUCCIONES" character varying(250),
					  "TOTAL A COBRAR" character varying(250),
					  "NUMERO DE CUENTA DEL TRABAJADOR" character varying(250)
					)';
				$resultado = pg_query($conn,$consulta);
				if(!$resultado){
					echo "<p>No se pudo ejecutar la consulta.</p>";
				}else{
					echo "<p>Consulta ejecutada con éxito.</p>";
				}
			}
		}
	}
	
	generar_tablas_recibo('2013');
?>