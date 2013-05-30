<?php
switch($_GET[act]){
  // Tampil jurusan Utama
	default:
	?>
	<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            var kelas = $("#kelas").val();
            $.ajax({
                url: "modul/dosen/perkuliahan/grafik_kelas/matakuliah.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#matakuliah").html(msg);
                }
            });
          });
        });
    </script>
    <?php
	echo "<div class='content'>
		<h3>Perkuliahan &#187; Statistik Kelas</h3>
		<br class='clear'/>
		<form action='$aksi_self' method='post'>
		<b>Tahun Ajaran</b>
		<select name='periode' id='periode'>";
		$q_periode=mssql_query("select periode_id from tm_periode where periode_aktif<> 'N' order by  periode_id desc");
		while($r_periode=mssql_fetch_array($q_periode))
		{
			if($r_periode[periode_id] == $_SESSION[periode])
				echo "<option value='$r_periode[periode_id]' selected='selected'>$r_periode[periode_id]</option>";
			else
				echo "<option value='$r_periode[periode_id]'>$r_periode[periode_id]</option>";
		}
		echo "</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Kelas</b>
		<select name='matakuliah' id='matakuliah'>";
		$q_matkul=mssql_query("SELECT DISTINCT tt_jadwal.matakuliah_id,
									tm_matakuliah.matakuliah_nama
								FROM tt_jadwal
									INNER JOIN tm_matakuliah ON tm_matakuliah.matakuliah_id = tt_jadwal.matakuliah_id
								WHERE tt_jadwal.periode_id='$periode'
									AND tt_jadwal.pegawai_kode='$_SESSION[pegawai_kode]'
								ORDER BY tt_jadwal.matakuliah_id
								");
		$i=1;
		while($r_matkul=mssql_fetch_array($q_matkul))
		{
			if($_POST[matakuliah] == "")
			{
				if($i == 1)
				{
					$matkul=$r_matkul[matakuliah_id];
				}
				echo "<option value='$r_matkul[matakuliah_id]'>$r_matkul[matakuliah_id] &nbsp;&nbsp;&nbsp; $r_matkul[matakuliah_nama]</option>";
			}
			else
			{		
				$matkul=$_POST[matakuliah];
				if($r_matkul[matakuliah_id] == $_POST[matakuliah])
				{
					echo "<option value='$r_matkul[matakuliah_id]' selected='selected'>$r_matkul[matakuliah_id] &nbsp;&nbsp;&nbsp; $r_matkul[matakuliah_nama]</option>";
				}
				else
				{
					echo "<option value='$r_matkul[matakuliah_id]'>$r_matkul[matakuliah_id] &nbsp;&nbsp;&nbsp; $r_matkul[matakuliah_nama]</option>";
				}
			}
			$i++;
		}
		echo "</select>
		<input type='submit' value='Submit'>
		<br class='clear'>
		<br class='clear'>
		<div id='chart_wrapper' class='chart_wrapper'></div>";
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
					WHERE (tt_jadwal.periode_id = '$periode') 
						AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]')
						AND (tt_jadwal.matakuliah_id = '$matkul')
					ORDER BY tt_jadwal.kelas_id");
		
		echo "<table id='graph_data' class='data' rel='bar' cellpadding='0' cellspacing='0' width='100%'>
			<caption>Periode $periode</caption>
			<thead>
				<tr>
					<td class='no_input'>&nbsp;</td>";
					$i=0;
					while($r=mssql_fetch_array($q))
					{
						echo"<th><a href='$aksi_self&act=kelas&periode=$periode&matakuliah=$matkul&kelas_id=$r[kelas_id]' title='Detail Statistik Nilai $matkul $r[kelas_id] $periode'>$r[kelas_id]</a></th>";
						$nilai_rata_rata[]	=round($r[nilai_rata_rata],2);
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
		</table>
		<!-- End bar chart table-->
	</div>";
	break;
	
	case "kelas":
	$q=mssql_query("
					SELECT kelas_id,
						AVG(nilai_uts) AS AVG_1,
						AVG(nilai_uas) AS AVG_2,
						AVG(nilai_1) AS AVG_3,
						AVG(nilai_2) AS AVG_4,
						AVG(nilai_3) AS AVG_5,
						AVG(nilai_4) AS AVG_6,
						AVG(nilai_5) AS AVG_7,
						AVG(nilai_6) AS AVG_8,
						AVG(nilai_7) AS AVG_9,
						AVG(nilai_8) AS AVG_10,
						AVG(nilai_9) AS AVG_11,
						AVG(nilai_10) AS AVG_12,
						AVG(nilai_rata_rata) AS AVG_13,
						MAX(nilai_uts) AS MAX_1,
						MAX(nilai_uas) AS MAX_2,
						MAX(nilai_1) AS MAX_3,
						MAX(nilai_2) AS MAX_4,
						MAX(nilai_3) AS MAX_5,
						MAX(nilai_4) AS MAX_6,
						MAX(nilai_5) AS MAX_7,
						MAX(nilai_6) AS MAX_8,
						MAX(nilai_7) AS MAX_9,
						MAX(nilai_8) AS MAX_10,
						MAX(nilai_9) AS MAX_11,
						MAX(nilai_10) AS MAX_12,
						MAX(nilai_rata_rata) AS MAX_13,
						MIN(nilai_uts) AS MIN_1,
						MIN(nilai_uas) AS MIN_2,
						MIN(nilai_1) AS MIN_3,
						MIN(nilai_2) AS MIN_4,
						MIN(nilai_3) AS MIN_5,
						MIN(nilai_4) AS MIN_6,
						MIN(nilai_5) AS MIN_7,
						MIN(nilai_6) AS MIN_8,
						MIN(nilai_7) AS MIN_9,
						MIN(nilai_8) AS MIN_10,
						MIN(nilai_9) AS MIN_11,
						MIN(nilai_10) AS MIN_12,
						MIN(nilai_rata_rata) AS MIN_13
					FROM tt_nilai
					WHERE kelas_id='$_GET[kelas_id]'
						AND periode_id='$periode'
						AND matakuliah_id='$_GET[matakuliah]'
					GROUP BY kelas_id
					");
	
	$r=mssql_fetch_array($q);
	for($i=1;$i<=13;$i++)
	{
		$AVG[]=round($r[AVG_."$i"],2);
		$MAX[]=$r[MAX_."$i"];
		$MIN[]=$r[MIN_."$i"];
	}
	echo "<div class='content'>
		<h3>Perkuliahan &#187; Statistik Kelas $_GET[matakuliah] $_GET[kelas_id]</h3>
		<br class='clear'/>
		<br class='clear'>
		<div id='chart_wrapper' class='chart_wrapper'></div>
		<table id='graph_data' class='data' rel='bar' cellpadding='0' cellspacing='0' width='100%'>
			<caption>Periode $_GET[kelas_id]</caption>
			<thead>
				<tr>
					<td class='no_input'>&nbsp;</td>
					<th>Nilai UTS</th>
					<th>Nilai UAS</th>
					<th>Nilai 1</th>
					<th>Nilai 2</th>
					<th>Nilai 3</th>
					<th>Nilai 4</th>
					<th>Nilai 5</th>
					<th>Nilai 6</th>
					<th>Nilai 7</th>
					<th>Nilai 8</th>
					<th>Nilai 9</th>
					<th>Nilai 10</th>
					<th>Nilai Rata-rata</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Rata-rata</th>";
					for($i=0;$i<=12;$i++)
					{
						echo "<td>$AVG[$i]</td>";
					}
				echo "</tr>
				<tr>
					<th>Tertinggi</th>";
					for($i=0;$i<=12;$i++)
					{
						echo "<td>$MAX[$i]</td>";
					}
				echo "</tr>
				<tr>
					<th>Terendah</th>";
					for($i=0;$i<=12;$i++)
					{
						echo "<td>$MIN[$i]</td>";
					}
				echo "</tr>
			</tbody>
		</table>
	<br class='clear'>
	<br class='clear'>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";					
	break;
}
?>
