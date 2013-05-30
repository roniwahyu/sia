<?php
	session_name(f68bac89085669468822b54a74b9b93e);
	session_start();
	if (empty($_SESSION['pegawai_kode']) || empty($_SESSION['pegawai_password']) || empty($_SESSION['comid']) || empty($_SESSION['userid']) || empty($_SESSION['periode']))
	{
		header("Location:index.html");
	}
?>