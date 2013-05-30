<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/library.php";
	include "../../../../config/fungsi_indotgl.php";
	
	$tanggal= tgl_insert($_POST[pegawai_tgl_lhr]);
	
	mssql_query("UPDATE tm_pegawai SET pegawai_tmpt_lhr		= '$_POST[pegawai_tmpt_lhr]',
									pegawai_tgl_lhr			= '$tanggal',
									agama_kode				= '$_POST[agama_kode]',
									pegawai_jln				= '$_POST[pegawai_jln]',
									pegawai_no				= '$_POST[pegawai_no]',
									pegawai_rt				= '$_POST[pegawai_rt]',
									pegawai_rw				= '$_POST[pegawai_rw]',
									pegawai_desa			= '$_POST[pegawai_desa]',
									pegawai_kecamatan		= '$_POST[pegawai_kecamatan]',
									propinsi_id				= '$_POST[propinsi_id]',
									kota_kode				= '$_POST[kota_kode]',
									pegawai_kodepos			= '$_POST[pegawai_kodepos]',
									pegawai_telp			= '$_POST[pegawai_telp]',
									pegawai_email			= '$_POST[pegawai_email]',
									pegawai_website			= '$_POST[pegawai_website]',
									goldarah_id				= '$_POST[goldarah_id]',
									lastupdate				= '$jam_waktu'
								WHERE pegawai_kode			= '$_SESSION[pegawai_kode]'
				");
	header("Location:../../../../media.php?departemen=public&menu=biodata&modul=biodata&notif=ok");
?>