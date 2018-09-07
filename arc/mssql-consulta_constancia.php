<?php
	require('../incluidos/bd-mssql.php');

	// Realizar una consulta simple
	$rh = mssql_query('USE RH');

	$campos = mssql_query('SELECT NombrePersonal, PrimerApellido, Cedula, FechaDeIngreso, NombreCargo, NombreGerencia, SueldoBasico FROM DetallePersonal WHERE Cedula="'.$ci.'"');
	$columnas = mssql_fetch_array($campos);

	if(!$columnas){
		echo "<p>Personal no encontrado en los registros de bd-mssql.</p>";
	}else{
		$nombre=$columnas[0];
		$apellido=$columnas[1];
		$ci=$columnas[2];
		$fechaingreso=$columnas[3];
		$cargo=$columnas[4];
		$gerencia=$columnas[5];
		$sueldobase=$columnas[6];
	}

	// Limpieza
	mssql_free_result($campos);
	mssql_close($enlace);
?>