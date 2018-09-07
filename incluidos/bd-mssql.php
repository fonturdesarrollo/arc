<?php

	// Servidor en el siguiente formato: <nombre del equipo>\<nombre de la instancia> o <dirección ip>,<puerto> cuando se esté usando un puerto no convencional
	$servidor = '172.16.7.185';

	// Conecta a MS-SQL Server 2005
	$enlace = mssql_connect($servidor, 'sa', 'vv2832f1');

	if (!$enlace) {
		die('Error conectando a bd-mssql.');
	}

?>