<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	
	$tanggal = tgl_insert($_POST[tanggal]);
	if($_GET[act]=='tambah')
	{
		mssql_query("INSERT INTO tt_jadwal_bimbingan(mahasiswa_nim,
											pegawai_kode,
											jadwal_bimbingan_tanggal,
											jadwal_bimbingan_catatan,
											periode_id,
											jam_id_awal,
											jam_id_akhir,
											ruang_id)
										VALUES('$_POST[mahasiswa_nim]',
											'$_SESSION[pegawai_kode]',
											'$tanggal',
											'$_POST[catatan]',
											'$_SESSION[periode]',
											'$_POST[jam_id_awal]',
											'$_POST[jam_id_akhir]',
											'$_POST[ruang_id]')
					");
	}
	elseif($_GET[act]=='ubah')
	{
		mssql_query("UPDATE tt_jadwal_bimbingan SET jadwal_bimbingan_tanggal='$tanggal', 
													mahasiswa_nim='$_POST[mahasiswa_nim]',
													jadwal_bimbingan_catatan='$_POST[catatan]',
													jam_id_awal='$_POST[jam_id_awal]',
													jam_id_akhir='$_POST[jam_id_akhir]',
													ruang_id='$_POST[ruang_id]'
					WHERE jadwal_bimbingan_id='$_POST[id]'");
	}
	elseif($_GET[act]=='hapus')
	{
		mssql_query("DELETE FROM tt_jadwal_bimbingan
					WHERE jadwal_bimbingan_id='$_GET[id]'");
	}
	header("location:../../../../media.php?departemen=dosen&menu=tgs_akhir&modul=jdw_bimbingan");
?>