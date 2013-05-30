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
		<th>Agenda Kegiatan</th>
		<th width='200px'>tanggal awal</th>
		<th width='200px' class='kanan'>tanggal akhir</th>
	</tr>";
	$no=1;
	$q_kalender=mssql_query("
							SELECT tt_kalender_akademik.kalender_akademik_awal, 
								tt_kalender_akademik.kalender_akademik_akhir, 
								tm_keterangan_kalender.keterangan_kalender_nama
							FROM tt_kalender_akademik 
								INNER JOIN tm_keterangan_kalender ON tt_kalender_akademik.keterangan_kalender_id = tm_keterangan_kalender.keterangan_kalender_id
							WHERE (tt_kalender_akademik.periode_id = '$_GET[periode]')
							ORDER BY tt_kalender_akademik.kalender_akademik_awal");
	while($r_kalender=mssql_fetch_array($q_kalender))
	{
		$awal = tgl_indo($r_kalender[kalender_akademik_awal]);
		$akhir = tgl_indo($r_kalender[kalender_akademik_akhir]);
		$line = tabel_antara($r_kalender[kalender_akademik_awal],$r_kalender[kalender_akademik_akhir],$no);
		echo "$line<td align='center'>$no</td>
		<td>$r_kalender[keterangan_kalender_nama]</td>
		<td>$awal</td>
		<td>$akhir</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='modul/public/akademik/kalender/pdf_kalender.php?periode=$_GET[periode]' target='_parent' title='Save Kalender Akademik'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='modul/public/akademik/kalender/print_kalender.php?periode=$_GET[periode]' target='_parent' title='Print Kalender Akademik'><img src='template/images/printer.png'> <b>Print</b></a>";
?>