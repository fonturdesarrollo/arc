<?php
	$conn = pg_connect("host=172.16.7.195 port=5432 dbname=SIMA user=cidesa password=cidesa");
	if (!$conn) {
	  echo "<p>Error conectando a bd-pg.</p>";
	  exit;
	}
?>