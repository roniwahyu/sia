<?php
	require_once "../../../../config/session.php";
	require_once "../../../../config/koneksi.php";
	require_once "../../../../config/fungsi_indotgl.php";
	$tanggal = tgl_insert($_POST[tanggal]);
	if($_GET[act]=='tambah')
	{
		mssql_query("INSERT INTO tt_jadwal_perwalian(periode_id,
											pegawai_kode,
											kelas_id,
											jadwal_perwalian_ket,
											jadwal_perwalian_aktif,
											jadwal_perwalian_tgl,
											jam_id_awal,
											jam_id_akhir,
											ruang_id)
										VALUES('$_SESSION[periode]',
											'$_SESSION[pegawai_kode]',
											'$_POST[kelas]',
											'$_POST[keterangan]',
											'$_POST[aktif]',
											'$tanggal',
											'$_POST[jam_id_awal]',
											'$_POST[jam_id_akhir]',
											'$_POST[ruang_id]')
					");
	}
	elseif($_GET[act]=='ubah')
	{
		mssql_query("UPDATE tt_jadwal_perwalian SET periode_id='$_SESSION[periode]', 
													kelas_id='$_POST[kelas]',
													jadwal_perwalian_ket='$_POST[keterangan]',
													jadwal_perwalian_aktif='$_POST[aktif]',
													jadwal_perwalian_tgl='$tanggal',
													jam_id_awal='$_POST[jam_id_awal]',
													jam_id_akhir='$_POST[jam_id_akhir]',													
													ruang_id='$_POST[ruang_id]'													
					WHERE jadwal_perwalian_id='$_POST[id]'");
	}
	elseif($_GET[act]=='hapus')
	{
		mssql_query("DELETE FROM tt_jadwal_perwalian
					WHERE jadwal_perwalian_id='$_GET[id]'");
	}
	elseif($_GET[act]=='aktif')
	{
		mssql_query("UPDATE tt_jadwal_perwalian SET jadwal_perwalian_aktif='Y'
					WHERE jadwal_perwalian_id='$_GET[id]'");
	}
	elseif($_GET[act]=='nonaktif')
	{
		mssql_query("UPDATE tt_jadwal_perwalian SET jadwal_perwalian_aktif='N'
					WHERE jadwal_perwalian_id='$_GET[id]'");
	}
	header("location:../../../../media.php?departemen=dosen&menu=perwalian&modul=jdw_perwalian");
?>