<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	$q_matkul=mssql_query("SELECT DISTINCT tt_jadwal.matakuliah_id,
								tm_matakuliah.matakuliah_nama
							FROM tt_jadwal
								INNER JOIN tm_matakuliah ON tm_matakuliah.matakuliah_id = tt_jadwal.matakuliah_id
							WHERE tt_jadwal.periode_id='$_GET[periode]'
								AND tt_jadwal.pegawai_kode='$_SESSION[pegawai_kode]'
							ORDER BY tt_jadwal.matakuliah_id
							");
	while($r_matkul=mssql_fetch_array($q_matkul))
	{
		echo "<option value='$r_matkul[matakuliah_id]'>$r_matkul[matakuliah_id] &nbsp;&nbsp;&nbsp; $r_matkul[matakuliah_nama]</option>";
	}
?>