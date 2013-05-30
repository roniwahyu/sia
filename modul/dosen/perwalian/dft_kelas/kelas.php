<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='100px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='100px'>kode kelas</th>
		<th width='100px'>kode dosen</th>
		<th>nama dosen</th>
		<th>sks</th>
		<th>kuota</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_kelas=mssql_query("
						SELECT DISTINCT tt_jadwal.kelas_id, 
							tt_jadwal.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_sks, 
							tt_jadwal.pegawai_kode, 
							tm_pegawai.pegawai_nama,
							(SELECT tm_ruang.ruang_max
								FROM tm_ruang 
								WHERE tm_ruang.ruang_id = tt_jadwal.ruang_id) AS ruang_max,
							(SELECT jadwal.jadwal_kuota
								FROM tt_jadwal AS jadwal 
								WHERE jadwal.jadwal_id = tt_jadwal.jadwal_id) AS ruang_kuota							
						FROM tt_jadwal 
							INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id 
							INNER JOIN tm_pegawai ON tt_jadwal.pegawai_kode = tm_pegawai.pegawai_kode
						WHERE (tt_jadwal.periode_id = '$_GET[periode]')
						ORDER BY tt_jadwal.matakuliah_id,
							tt_jadwal.kelas_id							
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$r_kelas[matakuliah_id]</td>
			<td>$r_kelas[matakuliah_nama]</td>
			<td>$r_kelas[kelas_id]</td>
			<td>$r_kelas[pegawai_kode]</td>
			<td>$r_kelas[pegawai_nama]</td>
			<td>$r_kelas[matakuliah_sks]</td>
			<td>$r_kelas[ruang_kuota]/$r_kelas[ruang_max]</td>
			<td><a href='?departemen=dosen&menu=perwalian&modul=dft_kelas&act=jadwal&periode=$_GET[periode]&kelas_id=$r_kelas[kelas_id]&matakuliah_id=$r_kelas[matakuliah_id]' title='Detail Jadwal $r_kelas[kelas_id] $r_kelas[matakuliah_nama]'><img src='template/images/detail.ico'></a>
			&nbsp;&nbsp;
				<a href='?departemen=dosen&menu=perwalian&modul=dft_kelas&act=mahasiswa&periode=$_GET[periode]&kelas_id=$r_kelas[kelas_id]&matakuliah_id=$r_kelas[matakuliah_id]' title='Detail $r_kelas[kelas_id]'><img src='template/images/chart_bar.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>