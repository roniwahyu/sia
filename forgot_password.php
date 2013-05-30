<?php
	include "config/koneksi.php";
	include "config/new_session.php";
	include "config/password.php";
	if (!ctype_alnum($_POST[username]))
	{
		  $link = 'index.php';
	}
	else
	{
		$password=mssql_query("SELECT pegawai_kode,pegawai_nama,pegawai_email FROM tm_pegawai WHERE pegawai_kode='$_POST[username]' AND pegawai_email='$_POST[email]' AND pegawai_aktif='Y'");
		$r=mssql_fetch_array($password);
		if ($_POST[username] == $r[pegawai_kode] && $_POST[email] == $r[pegawai_email])
		{
			$password = password_user($r[pegawai_kode]);
			mssql_query("UPDATE tm_pegawai SET pegawai_password='$password' WHERE pegawai_kode='$r[pegawai_kode]'");
			$pesan.="<br /><br />Sistem Informasi Akademik (SIAKAD) Politeknik Negeri Malang
					 <br /><br />$r[pegawai_nama] ($r[pegawai_kode]) telah menggunakan fasilitas forgot password.
					 <br />Password : $r[pegawai_kode]
					 <br /><br />Terima kasih
					 <br /><br />Admin";
			
			$subjek="Restore Password";
			
			// Kirim email dalam format HTML
			$dari = "From: admin@polinema.ac.id \n";
			$dari .= "Content-type: text/html \r\n";
			
			// Kirim email ke kustomer
			mail($_POST[email],$subjek,$pesan,$dari);
		}
		  $link = 'index.php';
	}
	header("Location:$link");
?>