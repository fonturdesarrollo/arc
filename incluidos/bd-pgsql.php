<?php
	$conn = pg_connect("host=127.0.0.1 port=5432 dbname=postgres user=postgres password=noseascomoyosoy");
	if (!$conn) {
	  echo "<p>Error conectando a bd-pg.</p>";
	  exit;
	}
?>