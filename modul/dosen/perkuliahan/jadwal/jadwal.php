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
                url: "modul/dosen/perkuliahan/jadwal/jadwal_data.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#jadwal").html(msg);
                }
            });
          });
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Jadwal</h3>
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
	<div id='jadwal'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='100px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>tipe</th>
		<th width='40px'>sks</th>
		<th>kode kelas</th>
		<th width='80px'>hari</th>
		<th width='100px'>jam</th>
		<th class='kanan' width='60px'>ruang</th>
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
							WHERE (tt_jadwal.periode_id = '$periode') 
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
	<br class='clear'>
	<br class='clear'>
	<a href='$pdf?periode=$periode' target='_parent' title='Save Jadwal'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='$print?periode=$periode' target='_blank' title='Print Jadwal'><img src='template/images/printer.png'> <b>Print</b></a>
	</div>
	</div>";
	break;
}
?>