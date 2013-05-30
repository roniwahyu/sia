<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
		$q=mssql_query("SELECT distinct tt_jadwal.kelas_id,
						(SELECT AVG(tt_nilai.nilai_rata_rata)
						FROM tt_nilai
						WHERE tt_nilai.periode_id = tt_jadwal.periode_id
							AND tt_nilai.matakuliah_id = tt_jadwal.matakuliah_id
							AND tt_nilai.kelas_id = tt_jadwal.kelas_id
						) AS nilai_rata_rata,
						(SELECT MAX(tt_nilai.nilai_rata_rata)
						FROM tt_nilai
						WHERE tt_nilai.periode_id = tt_jadwal.periode_id
							AND tt_nilai.matakuliah_id = tt_jadwal.matakuliah_id
							AND tt_nilai.kelas_id = tt_jadwal.kelas_id
						) AS nilai_max,
						(SELECT MIN(tt_nilai.nilai_rata_rata)
						FROM tt_nilai
						WHERE tt_nilai.periode_id = tt_jadwal.periode_id
							AND tt_nilai.matakuliah_id = tt_jadwal.matakuliah_id
							AND tt_nilai.kelas_id = tt_jadwal.kelas_id
						) AS nilai_min
					FROM tt_jadwal
					WHERE (tt_jadwal.periode_id = '$_GET[periode]') 
						AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]')
						AND (tt_jadwal.matakuliah_id = '$_GET[matakuliah]')
					ORDER BY tt_jadwal.kelas_id");
		
		echo "<div id='chart_wrapper' class='chart_wrapper'></div>
		<table id='graph_data' class='data' rel='bar' cellpadding='0' cellspacing='0' width='100%'>
			<caption>Periode $periode</caption>
			<thead>
				<tr>
					<td class='no_input'>&nbsp;</td>";
					$i=0;
					while($r=mssql_fetch_array($q))
					{
						echo"<th>$r[kelas_id]</th>";
						$nilai_rata_rata[]	=$r[nilai_rata_rata];
						$nilai_max[]		=$r[nilai_max];
						$nilai_min[]		=$r[nilai_min];
						$i++;
					}
					echo "</tr>
			</thead>
			<tbody>
				<tr>
					<th>Rata - rata</th>";
					for($j=0;$j<=$i-1;$j++)
					{
						echo"<td>$nilai_rata_rata[$j]</td>";
					}
					echo "</tr>
				<tr>
					<th>Tertinggi</th>";
					for($j=0;$j<=$i-1;$j++)
					{
						echo"<td>$nilai_max[$j]</td>";
					}
					echo "</tr>
				<tr>
					<th>Terendah</th>";
					for($j=0;$j<=$i-1;$j++)
					{
						echo"<td>$nilai_min[$j]</td>";
					}
					echo "</tr>
			</tbody>
		</table>";
?>
