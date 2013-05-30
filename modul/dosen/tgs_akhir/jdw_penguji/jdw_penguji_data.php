<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>nim</th>
		<th>nama</th>
		<th>tipe ujian</th>
		<th>tanggal</th>
		<th>waktu</th>
		<th>ruang</th>
		<th>pembimbing 1</th>
		<th>pembimbing 2</th>
		<th>penguji 1</th>
		<th>penguji 2</th>
		<th class='kanan'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("
								SELECT tt_jadwal_sidang.mahasiswa_nim, 
									tm_mahasiswa.mahasiswa_nama, 
									tt_jadwal_sidang.penguji_kode_1, 
									tt_jadwal_sidang.penguji_kode_2, 
									tt_jadwal_sidang.ujian_tipe_id, 
									tt_jadwal_sidang.jadwal_sidang_tanggal, 
									tt_jadwal_sidang.jam_id_awal, 
									tt_jadwal_sidang.jam_id_akhir, 
									tt_jadwal_sidang.ruang_id,
									tt_tugas_akhir.judul_file_ta1, 
									tt_tugas_akhir.judul_file_ta2, 
									tt_tugas_akhir.pegawai_kode_1 AS pembimbing_kode_1, 
									tt_tugas_akhir.pegawai_kode_2 AS pembimbing_kode_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_1)) AS pembimbing_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_2)) AS pembimbing_nama_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_jadwal_sidang.penguji_kode_1)) AS penguji_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_jadwal_sidang.penguji_kode_2)) AS penguji_nama_2,
									  (SELECT     jam_waktu
										FROM          tm_jam
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_awal)) AS jam_waktu_awal,
									  (SELECT     jam_waktu
										FROM          tm_jam AS tm_jam_1
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_akhir)) AS jam_waktu_akhir,
									  (SELECT     ujian_tipe_nama
										FROM          tm_ujian_tipe
										WHERE      (ujian_tipe_id = tt_jadwal_sidang.ujian_tipe_id)) AS ujian_tipe_nama
								FROM tt_jadwal_sidang 
									INNER JOIN tt_tugas_akhir ON tt_jadwal_sidang.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim 
									INNER JOIN tm_mahasiswa ON tt_jadwal_sidang.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
								WHERE     ((tt_jadwal_sidang.penguji_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_jadwal_sidang.penguji_kode_2 = '$_SESSION[pegawai_kode]'))
									AND tt_jadwal_sidang.periode_id='$_GET[periode]'
								ORDER BY tt_jadwal_sidang.jadwal_sidang_tanggal								
								");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_sidang_tanggal']);
		$line = tabel_jadwal($r_jadwal['jadwal_sidang_tanggal'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[mahasiswa_nim]</td>
			<td>$r_jadwal[mahasiswa_nama]</td>
			<td>$r_jadwal[ujian_tipe_nama]</td>
			<td>$tgl</td>
			<td>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td>$r_jadwal[ruang_id]</td>
			<td>$r_jadwal[pembimbing_nama_1] ($r_jadwal[pembimbing_kode_1])</td>
			<td>$r_jadwal[pembimbing_nama_2] ($r_jadwal[pembimbing_kode_2])</td>
			<td>$r_jadwal[penguji_nama_1] ($r_jadwal[penguji_kode_1])</td>
			<td>$r_jadwal[penguji_nama_2] ($r_jadwal[penguji_kode_2])</td>
			<td>";
			if($r_jadwal[ujian_tipe_id]=='ta1')
				echo"<a href='ta/$r_jadwal[judul_file_ta1]' title='Tugas Akhir $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/page_save.png'></a>";
			if($r_jadwal[ujian_tipe_id]=='ta2')
				echo"<a href='ta/$r_jadwal[judul_file_ta2]' title='Tugas Akhir $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/page_save.png'></a>";
			echo"</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='modul/dosen/tgs_akhir/jdw_penguji/pdf_jdw_penguji.php?periode=$_GET[periode]' target='_parent' title='Save Jadwal Penguji'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='modul/dosen/tgs_akhir/jdw_penguji/pdf_jdw_penguji.php?periode=$_GET[periode]' target='_parent' title='Print Jadwal Penguji'><img src='template/images/printer.png'> <b>Print</b></a>";
?>