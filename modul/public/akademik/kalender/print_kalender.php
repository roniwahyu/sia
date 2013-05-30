<?php	
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/fungsi_tanggal.php";

	echo "<link href='../../../../template/css/print.css' rel='stylesheet' type='text/css' media='all'>
	<body onLoad='javascript:window.print()'>
	<table>
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
		echo "<tr> 
		<td align='center'>$no</td>
		<td>$r_kalender[keterangan_kalender_nama]</td>
		<td>$awal</td>
		<td>$akhir</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</body>";
?>