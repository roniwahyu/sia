<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";
	include "../../../../config/fungsi_indotgl.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>tanggal</th>
		<th>tipe ujian</th>
		<th>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>kode kelas</th>
		<th>waktu</th>
		<th class='kanan'>ruang</th>
	</tr>";
	$q_jadwal=mssql_query("SELECT DISTINCT tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.jadwal_ujian_tanggal, 
								tt_jadwal_ujian.jam_id_awal, 
								tt_jadwal_ujian.matakuliah_id, 
								tt_jadwal_ujian.kelas_id, 
								tm_ujian_tipe.ujian_tipe_nama,
								tt_jadwal_ujian.ruang_id, 
								tm_matakuliah.matakuliah_nama,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_awal) AS jam_waktu_awal,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_akhir) AS jam_waktu_akhir
							FROM tt_jadwal_ujian 
								INNER JOIN tm_matakuliah ON tt_jadwal_ujian.matakuliah_id = tm_matakuliah.matakuliah_id
								INNER JOIN tm_ujian_tipe ON tt_jadwal_ujian.ujian_tipe_id=tm_ujian_tipe.ujian_tipe_id
								INNER JOIN tt_jadwal ON tt_jadwal_ujian.matakuliah_id = tt_jadwal.matakuliah_id
									AND tt_jadwal_ujian.kelas_id = tt_jadwal.kelas_id
									AND tt_jadwal_ujian.periode_id = tt_jadwal.periode_id
									AND tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]'
							WHERE (tt_jadwal_ujian.periode_id = '$_GET[periode]')
								AND (tt_jadwal_ujian.ujian_tipe_id LIKE '$_GET[ujian_tipe]')
							ORDER BY tt_jadwal_ujian.jadwal_ujian_tanggal,
								tt_jadwal_ujian.jam_id_awal,
								tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.matakuliah_id,
								tt_jadwal_ujian.kelas_id
"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal[jadwal_ujian_tanggal]);
		$line =tabel_jadwal($r_jadwal[jadwal_ujian_tanggal],$no);
		echo "$line<td align='center'>$no</td>
			<td>$tgl</td>
			<td>$r_jadwal[ujian_tipe_nama]</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>
			<td>$r_jadwal[kelas_id]</td>
			<td>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td>$r_jadwal[ruang_id]</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='modul/dosen/perkuliahan/jdw_ujian/pdf_jdw_ujian.php?periode=$_GET[periode]&ujian_tipe=$_GET[ujian_tipe]' target='_parent' title='Save Jadwal Ujian'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='modul/dosen/perkuliahan/jdw_ujian/print_jdw_ujian.php?periode=$_GET[periode]&ujian_tipe=$_GET[ujian_tipe]' target='_parent' title='Print Jadwal Ujian'><img src='template/images/printer.png'> <b>Print</b></a>";
?>