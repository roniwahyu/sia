<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='130px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='100px'>kode kelas</th>
		<th>sks</th>
		<th class='kanan' width='90px'>&nbsp;</th>
	</tr>";
	$q_jadwal=mssql_query("SELECT distinct tt_jadwal.kelas_id,
								tt_jadwal.matakuliah_id,
								tm_matakuliah.matakuliah_nama,
								tm_matakuliah.matakuliah_sks
							FROM tt_jadwal
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_jadwal.periode_id = '$_GET[periode]') 
								AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]')
							ORDER BY tt_jadwal.matakuliah_id,
								tt_jadwal.kelas_id
							");
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>
			<td>$r_jadwal[kelas_id]</td>
			<td align='center'>$r_jadwal[matakuliah_sks]</td>
			<td><a href='?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=absensi&periode=$_GET[periode]&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Absensi $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/detail.ico'></a>
			&nbsp;&nbsp;
			<a href='?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=mahasiswa&periode=$_GET[periode]&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Mahasiswa $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/group.png'></a>
			&nbsp;&nbsp;
			<a href='?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=nilai&periode=$_GET[periode]&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Nilai $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/chart_bar.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>