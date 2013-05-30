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
		<th width='130px'>periode</th>
		<th width='120px'>kode kelas</th>
		<th width='150px'>tanggal awal</th>
		<th width='150px'>tanggal akhir</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT *
							FROM tt_jadwal_krs
							WHERE periode_id='$_GET[periode]'
							ORDER BY jadwal_krs_awal ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$awal = tgl_indo($r_jadwal['jadwal_krs_awal']);
		$akhir = tgl_indo($r_jadwal['jadwal_krs_akhir']);
		$line = tabel_antara($r_jadwal['jadwal_krs_awal'],$r_jadwal['jadwal_krs_akhir'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[periode_id]</td>
			<td align='center'>$r_jadwal[kelas_id]</td>
			<td>$awal</td>
			<td>$akhir</td><td><a href='?departemen=dosen&menu=perwalian&modul=set_krs&act=ubah&id=$r_jadwal[jadwal_krs_id]' title='Edit'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='modul/dosen/perwalian/set_krs/aksi_set_krs.php?act=hapus&id=$r_jadwal[jadwal_krs_id]' title='Hapus' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal KRS kelas $r_jadwal[kelas_id]?')\"><img src='template/images/icon_delete.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>