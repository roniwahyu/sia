<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	require_once "../../../../config/fungsi_indotgl.php";
	$tgl_awal= tgl_insert($_POST[tanggal_awal]);
	$tgl_akhir= tgl_insert($_POST[tanggal_akhir]);
	if($_GET[act]=='tambah')
	{
		mssql_query("INSERT INTO tt_jadwal_krs(periode_id,
											kelas_id,
											jadwal_krs_awal,
											jadwal_krs_akhir)
										VALUES('$_SESSION[periode]',
											'$_POST[kelas]',
											'$tgl_awal',
											'$tgl_akhir')
					");
	}
	elseif($_GET[act]=='ubah')
	{
		mssql_query("UPDATE tt_jadwal_krs SET periode_id='$_SESSION[periode]', 
													kelas_id='$_POST[kelas]',
													jadwal_krs_awal='$tgl_awal',													
													jadwal_krs_akhir='$tgl_akhir'													
					WHERE jadwal_krs_id='$_POST[id]'");
	}
	elseif($_GET[act]=='hapus')
	{
		mssql_query("DELETE FROM tt_jadwal_krs
					WHERE jadwal_krs_id='$_GET[id]'");
	}
	header("location:../../../../media.php?departemen=dosen&menu=perwalian&modul=set_krs");
?>