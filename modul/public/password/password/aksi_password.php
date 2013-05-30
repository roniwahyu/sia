<?php
	include "../../../../config/session.php";
	include "../../../../config/reg_sess.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/library.php";
	include "../../../../config/password.php";
	
	$pass_lama= password_user($_POST[pass_lama]);
	if($pass_lama == $_SESSION[pegawai_password])
	{
		$pass_baru= password_user($_POST[pass_baru]);
		$pass_baru_konfirm= password_user($_POST[pass_baru_konfirm]);
		if($pass_baru == $pass_baru_konfirm)
		{
			mssql_query("UPDATE tm_pegawai SET pegawai_password	= '$pass_baru',
												lastupdate		= '$jam_waktu'
											WHERE pegawai_kode	= '$_SESSION[pegawai_kode]'
						");
						
			reg_sess("pegawai_password",$pass_baru);
			
			$notif='ok';
		}
		else
		{
			$notif='mid';
		}
	}
	else
	{
		$notif='begin';
	}
	header("Location:../../../../media.php?departemen=public&menu=password&modul=password&notif=$notif");
?>