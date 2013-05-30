<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	$q_kelas=mssql_query("
						SELECT tm_kelas.kelas_id, 
							tm_kelas.kelas_nama 
						FROM tm_kelas INNER 
							JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
						WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
							AND (tm_kelas.periode_id like '$_GET[angkatan]')							
						");
	echo "<option value='%'>SEMUA</option>";
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		echo "<option value='$r_kelas[kelas_id]'>$r_kelas[kelas_nama]</option>";
	}
?>