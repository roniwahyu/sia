<?php	
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/tabel.php";

	echo "<link href='../../../../template/css/print.css' rel='stylesheet' type='text/css' media='all'>
	<body onLoad='javascript:window.print()'>
	<table>
	<tbody>
	<tr align='center'>
		<th>no</th>
		<th>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>tipe</th>
		<th>sks</th>
		<th>kode kelas</th>
		<th>hari</th>
		<th>jam</th>
		<th>ruang</th>
	</tr>";
	$q_jadwal=mssql_query("SELECT tt_jadwal.kelas_id, 
								tt_jadwal.matakuliah_id, 
								tm_jam.jam_waktu, 
								tm_hari.hari_nama, 
								tt_jadwal.ruang_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks,
								tm_matakuliah.matakuliah_jam,
								tm_matakuliah.matakuliah_tipe,
								tm_hari.hari_urutan
							FROM tt_jadwal 
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
								INNER JOIN tm_hari ON tt_jadwal.hari_id=tm_hari.hari_id
								INNER JOIN tm_jam ON tm_jam.jam_id=tt_jadwal.jam_id 
							WHERE (tt_jadwal.periode_id = '$_GET[periode]') 
								AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]') 
							ORDER BY tm_hari.hari_urutan, tm_jam.jam_id ASC"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$akhir=$r_jadwal[jam_waktu]+$r_jadwal[matakuliah_jam];
		if($akhir < 10)
		{
			$akhir="0$akhir.00";
		}
		else
		{
			$akhir="$akhir.00";
		}
		$line =tabel_hari($r_jadwal[hari_urutan],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>";
			if($r_jadwal[matakuliah_tipe]=='T')
				echo "<td align='center'>Teori</td>";
			else
				echo "<td align='center'>Praktikum</td>";
			echo "<td align='center'>$r_jadwal[matakuliah_sks]</td>
			<td>$r_jadwal[kelas_id]</td>
			<td>$r_jadwal[hari_nama]</td>
			<td>$r_jadwal[jam_waktu] - $akhir</td>
			<td>$r_jadwal[ruang_id]</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</body>";
?>
