<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='70px'>nim</th>
		<th width='130px'>nama</th>
		<th width='130px'>pembimbing 1</th>
		<th width='130px'>pembimbing 2</th>
		<th>catatan</th>
		<th width='100px'>tanggal</th>
		<th width='50px'>ruang</th>
		<th width='90px'>waktu</th>
		<th width='60px' class='kanan'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT tt_jadwal_bimbingan.*,
								tt_tugas_akhir.pegawai_kode_1,
								tt_tugas_akhir.pegawai_kode_2,
								(SELECT tm_pegawai.pegawai_nama
									FROM tm_pegawai
									WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_1) AS pegawai_nama_1,
								(SELECT tm_pegawai.pegawai_nama
									FROM tm_pegawai
									WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_2) AS pegawai_nama_2,
								tm_mahasiswa.mahasiswa_nama,
								(SELECT tm_jam.jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_bimbingan.jam_id_awal) AS jam_waktu_awal,
								(SELECT tm_jam.jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_bimbingan.jam_id_akhir) AS jam_waktu_akhir								
							FROM tt_jadwal_bimbingan
								INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_jadwal_bimbingan.mahasiswa_nim
								INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim=tt_tugas_akhir.mahasiswa_nim
							WHERE tt_jadwal_bimbingan.periode_id='$_GET[periode]'
								AND tt_jadwal_bimbingan.pegawai_kode='$_SESSION[pegawai_kode]'
								AND tt_jadwal_bimbingan.mahasiswa_nim LIKE '$_GET[mahasiswa]'
							ORDER BY tt_jadwal_bimbingan.jadwal_bimbingan_tanggal ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_bimbingan_tanggal']);
		$line = tabel_jadwal($r_jadwal['jadwal_bimbingan_tanggal'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[mahasiswa_nim]</td>
			<td>$r_jadwal[mahasiswa_nama]</td>
			<td>$r_jadwal[pegawai_nama_1] ($r_jadwal[pegawai_kode_1])</td>
			<td>$r_jadwal[pegawai_nama_2] ($r_jadwal[pegawai_kode_2])</td>
			<td>$r_jadwal[jadwal_bimbingan_catatan]</td>
			<td>$tgl</td>
			<td align='center'>$r_jadwal[ruang_id]</td>
			<td align='center'>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td><a href='?departemen=dosen&menu=tgs_akhir&modul=jdw_bimbingan&act=ubah&id=$r_jadwal[jadwal_bimbingan_id]' title='Edit jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='modul/dosen/tgs_akhir/jdw_bimbingan/aksi_jdw_bimbingan.php?act=hapus&id=$r_jadwal[jadwal_bimbingan_id]' title='Hapus  jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]?')\"><img src='template/images/icon_delete.png'></a></td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>