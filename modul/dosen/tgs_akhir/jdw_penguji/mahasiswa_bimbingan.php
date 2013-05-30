<?php
	include '../../../../config/session.php';
	include '../../../../config/koneksi.php';
	$q_mhs=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim, 
						tm_mahasiswa.mahasiswa_nama
						FROM tm_mahasiswa 
							INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim AND tt_tugas_akhir.periode_id = '$_GET[periode]'
						WHERE (tt_tugas_akhir.pegawai_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_tugas_akhir.pegawai_kode_2 = '$_SESSION[pegawai_kode]')
						ORDER BY tm_mahasiswa.mahasiswa_nim 
					");
	while($r_mhs=mssql_fetch_array($q_mhs))
	{
		echo "<option value='$r_mhs[mahasiswa_nim]'>$r_mhs[mahasiswa_nim]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$r_mhs[mahasiswa_nama]</option>";
	}
?>