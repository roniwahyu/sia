<?php
	include "koneksi.php";
	session_name(f68bac89085669468822b54a74b9b93e);
	session_start();
	if (($_SESSION['pegawai_kode'] !="") && ($_SESSION['pegawai_password'] !="") && ($_SESSION['comid'] !="") && ($_SESSION['userid'] !="") && ($_SESSION[periode] !=""))
	{
		$login=mssql_query("SELECT pegawai_kode,pegawai_password,comid,userid FROM tm_pegawai WHERE pegawai_kode='$_SESSION[pegawai_kode]' AND pegawai_aktif='Y'");
		$r=mssql_fetch_array($login);
	
		$q_periode=mssql_query("SELECT periode_id FROM tm_periode WHERE periode_aktif='Y'");
		$p=mssql_fetch_array($q_periode);
		
		if($_SESSION['pegawai_kode'] == $r[pegawai_kode] && $_SESSION['pegawai_password'] == $r[pegawai_password] && $_SESSION['comid'] == $r[comid] && $_SESSION['userid'] == $r[userid] && $_SESSION[periode]== $p[periode_id])
		{
			header("Location:media.php?departemen=public&menu=akademik&modul=pengumuman");
		}
	}
?>