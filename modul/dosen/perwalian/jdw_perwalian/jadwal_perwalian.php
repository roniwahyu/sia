<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='120px'>kode kelas</th>
		<th>tanggal</th>
		<th width='100px'>waktu</th>
		<th width='70px'>ruang</th>
		<th>keterangan</th>
		<th width='50px'>aktif</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT tt_jadwal_perwalian.*,
							(SELECT tm_jam.jam_waktu
							FROM tm_jam
							WHERE tm_jam.jam_id=tt_jadwal_perwalian.jam_id_awal) AS jam_waktu_awal,
							(SELECT tm_jam.jam_waktu
							FROM tm_jam
							WHERE tm_jam.jam_id=tt_jadwal_perwalian.jam_id_akhir) AS jam_waktu_akhir
						FROM tt_jadwal_perwalian
							INNER JOIN tm_kelas ON tm_kelas.kelas_id = tt_jadwal_perwalian.kelas_id
								AND tm_kelas.periode_id LIKE '$_GET[angkatan]'
						WHERE tt_jadwal_perwalian.periode_id='$_GET[periode]'
							AND tt_jadwal_perwalian.pegawai_kode='$_SESSION[pegawai_kode]'
							AND tt_jadwal_perwalian.kelas_id LIKE '$_GET[kelas]'
						ORDER BY jadwal_perwalian_tgl ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_perwalian_tgl']);
		$line = tabel_jadwal($r_jadwal['jadwal_perwalian_tgl'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[kelas_id]</td>
			<td>$tgl</td>
			<td align='center'>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td align='center'>$r_jadwal[ruang_id]</td>
			<td>$r_jadwal[jadwal_perwalian_ket]</td>";
			if($r_jadwal[jadwal_perwalian_aktif]=='Y')
				echo "<td align='center'><a href='$aksi?act=nonaktif&id=$r_jadwal[jadwal_perwalian_id]' title='Aktif'><img src='template/images/icon_accept.png'></a></td>";
			else
				echo "<td align='center'><a href='$aksi?act=aktif&id=$r_jadwal[jadwal_perwalian_id]' title='Non Aktif'><img src='template/images/cancel.png'></a></td>";
			echo "<td><a href='?departemen=dosen&menu=perwalian&modul=jdw_perwalian&act=ubah&id=$r_jadwal[jadwal_perwalian_id]' title='Edit'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='modul/dosen/perwalian/jdw_perwalian/aksi_jdw_perwalian.php?act=hapus&id=$r_jadwal[jadwal_perwalian_id]' title='Hapus' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal Perwalian kelas $r_jadwal[kelas_id]?')\"><img src='template/images/icon_delete.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>