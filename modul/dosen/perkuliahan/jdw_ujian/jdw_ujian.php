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
            var ujian_tipe = $("#ujian_tipe").val();
            $.ajax({
                url: "modul/dosen/perkuliahan/jdw_ujian/jdw_ujian_data.php",
                data: "periode="+periode+"&ujian_tipe="+ujian_tipe,
                cache: false,
                success: function(msg){
                    $("#jadwal").html(msg);
                }
            });
          });

          $("#ujian_tipe").change(function(){
            var periode = $("#periode").val();
            var ujian_tipe = $("#ujian_tipe").val();
            $.ajax({
                url: "modul/dosen/perkuliahan/jdw_ujian/jdw_ujian_data.php",
                data: "periode="+periode+"&ujian_tipe="+ujian_tipe,
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
	<h3>Perkuliahan &#187; Jadwal Ujian</h3>
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
	echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b>Tipe Ujian</b><select name='ujian_tipe' id='ujian_tipe'>
		<option value='%'>SEMUA</option>
		<option value='uts'>UTS</option>
		<option value='uas'>UAS</option>
	</select>
	<br class='clear'>
	<br class='clear'>
	<div id='jadwal'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>tanggal</th>
		<th>tipe ujian</th>
		<th>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>kode kelas</th>
		<th>waktu</th>
		<th class='kanan'>ruang</th>
	</tr>";
	$q_jadwal=mssql_query("SELECT DISTINCT tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.jadwal_ujian_tanggal, 
								tt_jadwal_ujian.jam_id_awal, 
								tt_jadwal_ujian.matakuliah_id, 
								tt_jadwal_ujian.kelas_id, 
								tm_ujian_tipe.ujian_tipe_nama,
								tt_jadwal_ujian.ruang_id, 
								tm_matakuliah.matakuliah_nama,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_awal) AS jam_waktu_awal,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_akhir) AS jam_waktu_akhir
							FROM tt_jadwal_ujian 
								INNER JOIN tm_matakuliah ON tt_jadwal_ujian.matakuliah_id = tm_matakuliah.matakuliah_id
								INNER JOIN tm_ujian_tipe ON tt_jadwal_ujian.ujian_tipe_id=tm_ujian_tipe.ujian_tipe_id
								INNER JOIN tt_jadwal ON tt_jadwal_ujian.matakuliah_id = tt_jadwal.matakuliah_id
									AND tt_jadwal_ujian.kelas_id = tt_jadwal.kelas_id
									AND tt_jadwal_ujian.periode_id = tt_jadwal.periode_id
									AND tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]'
							WHERE (tt_jadwal_ujian.periode_id = '$periode')
							ORDER BY tt_jadwal_ujian.jadwal_ujian_tanggal,
								tt_jadwal_ujian.jam_id_awal,
								tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.matakuliah_id,
								tt_jadwal_ujian.kelas_id
"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal[jadwal_ujian_tanggal]);
		$line =tabel_jadwal($r_jadwal[jadwal_ujian_tanggal],$no);
		echo "$line<td align='center'>$no</td>
			<td>$tgl</td>
			<td>$r_jadwal[ujian_tipe_nama]</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>
			<td>$r_jadwal[kelas_id]</td>
			<td>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td>$r_jadwal[ruang_id]</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='$pdf?periode=$periode&ujian_tipe=%' target='_parent' title='Save Jadwal Ujian'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='$print?periode=$periode&ujian_tipe=%' target='_blank' title='Print Jadwal Ujian'><img src='template/images/printer.png'> <b>Print</b></a>
	</div>
	</div>";
	break;
}
?>