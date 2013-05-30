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
            $.ajax({
                url: "modul/dosen/perkuliahan/matkul/matakuliah.php",
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
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Mata Kuliah</h3>
	<br class='clear'/>
	<b>Tahun Ajaran</b>
	<select name=periode id='periode'>";
	$q_periode=mssql_query("select periode_id from tm_periode where periode_aktif<> 'N' order by  periode_id desc");
	while($r_periode=mssql_fetch_array($q_periode))
	{
		if($r_periode[periode_id] == $_SESSION[periode])
			echo "<option value='$r_periode[periode_id]' selected='selected'>$r_periode[periode_id]</option>";
		else
			echo "<option value='$r_periode[periode_id]'>$r_periode[periode_id]</option>";
	}
	echo "</select>
	<br class='clear'>
	<br class='clear'>
	<div id='matakuliah'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='150px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='60px'>sks</th>
		<th class='kanan' width='30px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_matkul=mssql_query("
							SELECT DISTINCT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks
							FROM tt_jadwal 
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]') 
								AND (tt_jadwal.periode_id = '$periode')
							");
	while($r_matkul=mssql_fetch_array($q_matkul))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center' class='merah'>$r_matkul[matakuliah_id]</td>
			<td>$r_matkul[matakuliah_nama]</td>
			<td align='center'>$r_matkul[matakuliah_sks]</td>
			<td><a href='$aksi_self&act=presentase&periode=$periode&matkul_id=$r_matkul[matakuliah_id]' title='Detail $r_matkul[matakuliah_id] $r_matkul[matakuliah_nama]'><img src='template/images/detail.ico'></a></td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br class='clear'>
	<br class='clear'>
	</div>";
    break;

	case "presentase":
	$periode=$_GET[periode];
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Mata Kuliah &#187; Penilaian</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>sks</th><th>uts</th>
		<th>uas</th>
		<th>nilai 1</th>
		<th>nilai 2</th>
		<th>nilai 3</th>
		<th>nilai 4</th>
		<th>nilai 5</th>
		<th>nilai 6</th>
		<th>nilai 7</th>
		<th>nilai 8</th>
		<th>nilai 9</th>
		<th>nilai 10</th>
		<th class='kanan'>total</th>
	</tr>";
	$no=1;
	$q_matkul=mssql_query("
						SELECT DISTINCT 
							tt_jadwal.periode_id, 
							tm_matakuliah.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_sks, 
							tt_prodi_nilai.nilai_uts, 
							tt_prodi_nilai.nilai_uas, 
							tt_prodi_nilai.nilai_1, 
							tt_prodi_nilai.nilai_2, 
							tt_prodi_nilai.nilai_3, 
							tt_prodi_nilai.nilai_4, 
							tt_prodi_nilai.nilai_5, 
							tt_prodi_nilai.nilai_6, 
							tt_prodi_nilai.nilai_7, 
							tt_prodi_nilai.nilai_8, 
							tt_prodi_nilai.nilai_9, 
							tt_prodi_nilai.nilai_10
						FROM tt_jadwal 
							INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id 
							INNER JOIN tt_prodi_nilai ON tm_matakuliah.matakuliah_id = tt_prodi_nilai.matakuliah_id
						WHERE (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]') 
							AND (tt_jadwal.periode_id = '$periode') 
							AND (tt_jadwal.periode_id = tt_prodi_nilai.periode_id) 
							AND (tt_jadwal.matakuliah_id = '$_GET[matkul_id]')							");
	while($r_matkul=mssql_fetch_array($q_matkul))
	{
		$line =tabel_normal($no);
		$total=$r_matkul[nilai_uts]+$r_matkul[nilai_uas]+$r_matkul[nilai_1]+$r_matkul[nilai_2]+$r_matkul[nilai_3]+$r_matkul[nilai_4]+$r_matkul[nilai_5]+$r_matkul[nilai_6]+$r_matkul[nilai_7]+$r_matkul[nilai_8]+$r_matkul[nilai_9]+$r_matkul[nilai_10];
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_matkul[matakuliah_id]</td>
			<td>$r_matkul[matakuliah_nama]</td>
			<td align='center'>$r_matkul[matakuliah_sks]</td>
			<td align='center'>$r_matkul[nilai_uts]</td>
			<td align='center'>$r_matkul[nilai_uas]</td>
			<td align='center'>$r_matkul[nilai_1]</td>
			<td align='center'>$r_matkul[nilai_2]</td>
			<td align='center'>$r_matkul[nilai_3]</td>
			<td align='center'>$r_matkul[nilai_4]</td>
			<td align='center'>$r_matkul[nilai_5]</td>
			<td align='center'>$r_matkul[nilai_6]</td>
			<td align='center'>$r_matkul[nilai_7]</td>
			<td align='center'>$r_matkul[nilai_8]</td>
			<td align='center'>$r_matkul[nilai_9]</td>
			<td align='center'>$r_matkul[nilai_10]</td>
			<td align='center'>$total</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br>
	<table class='input' cellpadding='0' cellspacing='0' width='300px'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>&nbsp;</th>
		<th>nilai min</th>
		<th class='kanan'>nilai max</th>
	<tr>";
	$q_tipenilai=mssql_query("SELECT nilai_tipe_id, 
								nilai_tipe_min, 
								nilai_tipe_max
							FROM tm_nilai_tipe");
	$no=1;
	while($r_tipenilai=mssql_fetch_array($q_tipenilai))
	{
		$line =tabel_normal($no);
		echo "$line<th>$r_tipenilai[nilai_tipe_id]</th><td>$r_tipenilai[nilai_tipe_min]</td><td>$r_tipenilai[nilai_tipe_max]</td><tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;	
}
?>
