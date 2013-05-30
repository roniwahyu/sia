<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";
	
	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='150px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='60px'>sks</th>
		<th class='kanan' width='30px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_matkul=mssql_query("
							SELECT DISTINCT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks
							FROM tt_jadwal 
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]') 
								AND (tt_jadwal.periode_id = '$_GET[periode]')
							");
	while($r_matkul=mssql_fetch_array($q_matkul))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_matkul[matakuliah_id]</td>
			<td>$r_matkul[matakuliah_nama]</td>
			<td align='center'>$r_matkul[matakuliah_sks]</td>
			<td><a href='?departemen=dosen&menu=perkuliahan&modul=matkul&act=presentase&periode=$periode&matkul_id=$r_matkul[matakuliah_id]' title='Detail $r_matkul[matakuliah_id] $r_matkul[matakuliah_nama]'><img src='template/images/detail.ico'></a></td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>