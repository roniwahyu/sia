<?php
include "koneksi.php";
include "password.php";
include "reg_sess.php";	

function login($username,$password)
{

		
	if (!ctype_alnum($username) OR !ctype_alnum($password))
	{
		  $link = 'index.php';
	}
	else
	{		
		$password = password_user($password);
		
		$login=mssql_query("SELECT pegawai_kode,pegawai_password,comid,userid FROM tm_pegawai WHERE pegawai_kode='$username' AND pegawai_password='$password' AND pegawai_aktif='Y'");
		$r=mssql_fetch_array($login);
		
		if ($username == $r[pegawai_kode] && $password == $r[pegawai_password])
		{
			//periode aktif
			$periode=mssql_query("SELECT periode_id FROM tm_periode WHERE periode_aktif='Y'");
			$p=mssql_fetch_array($periode);
			
			reg_sess("periode",$p[periode_id]);
			reg_sess("pegawai_kode",$r[pegawai_kode]);
			reg_sess("pegawai_password",$r[pegawai_password]);
			reg_sess("comid",$r[comid]);
			reg_sess("userid",$r[userid]);
			
			$link = 'media.php?departemen=public&menu=akademik&modul=pengumuman';
		}
		else
		{
		  $link = 'index.php';
		}
	}
	return $link;		
}

/*function old_login($username,$password,$comid,$userid,$periode)
{
	$login=mssql_query("SELECT pegawai_kode,pegawai_password,comid,userid FROM tm_pegawai WHERE pegawai_kode='$username' AND pegawai_aktif='Y'");
	$r=mssql_fetch_array($login);

	$q_periode=mssql_query("SELECT periode_id FROM tm_periode WHERE periode_aktif='Y'");
	$p=mssql_fetch_array($q_periode);
	
	if($username == $r[pegawai_kode] && $password == $r[pegawai_password] && $comid == $r[comid] && $userid == $r[userid] && $periode == $p[periode_id])
	{
		$link = 'media.php?departemen=public&menu=biodata&modul=biodata';
	}
	return $link;
}*/
?>