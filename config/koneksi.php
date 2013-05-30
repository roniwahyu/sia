<?php
	$mssqlHost = "MAHEN-INV\SQLEXPRESS";
	$mssqlUser = "sa";
	$mssqlPass = "mahen";
	$mssqlDB = "kesiakad";
	$link = mssql_connect($mssqlHost,$mssqlUser,$mssqlPass) or die ('Tidak dapat melakukan koneksi SQL Server on '.$mssqlHost.' '. mssql_get_last_message());
	$db = mssql_select_db($mssqlDB, $link) or die("Tidak dapat menggunakan database");
?>