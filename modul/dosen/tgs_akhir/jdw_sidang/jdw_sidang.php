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
            	var mahasiswa = $("#mahasiswa").val();
				$.ajax({
					url: "modul/dosen/tgs_akhir/jdw_sidang/mahasiswa_bimbingan.php",
					data: "periode="+periode,
					cache: false,
					success: function(msg){
						//jika data sukses diambil dari server kita tampilkan
						//di <select id=kota>
						$("#mahasiswa").html(msg);
					}
            	});				

            	$.ajax(
				{
					url: "modul/dosen/tgs_akhir/jdw_sidang/jdw_sidang_data.php",
					data: "periode="+periode+"&mahasiswa="+mahasiswa,
					cache: false,
					success: function(msg)
					{
						$("#jdw_sidang").html(msg);
                	}
            	});				
          });
        	$("#mahasiswa").change(function()
			{
            	var periode = $("#periode").val();
            	var mahasiswa = $("#mahasiswa").val();
            	$.ajax(
				{
					url: "modul/dosen/tgs_akhir/jdw_sidang/jdw_sidang_data.php",
					data: "periode="+periode+"&mahasiswa="+mahasiswa,
					cache: false,
					success: function(msg)
					{
						$("#jdw_sidang").html(msg);
                	}
            	});				
			});
		  
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Jadwal Sidang</h3>
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
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Mahasiswa</b>
	<select name=mahasiswa_nim id='mahasiswa'>
	<option value='%'>SEMUA MAHASISWA</option>";
	$q_mhs=mssql_query("
					SELECT tm_mahasiswa.mahasiswa_nim, 
						tm_mahasiswa.mahasiswa_nama
					FROM tm_mahasiswa 
						INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim AND tt_tugas_akhir.periode_id = '$periode'
					WHERE (tt_tugas_akhir.pegawai_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_tugas_akhir.pegawai_kode_2 = '$_SESSION[pegawai_kode]')
					ORDER BY tm_mahasiswa.mahasiswa_nim 
					");
	while($r_mhs=mssql_fetch_array($q_mhs))
	{
		echo "<option value='$r_mhs[mahasiswa_nim]'>$r_mhs[mahasiswa_nim]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$r_mhs[mahasiswa_nama]</option>";
	}
	echo "</select>
	<br class='clear'>
	<br class='clear'>
	<div id='jdw_sidang'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>nim</th>
		<th>nama</th>
		<th>tipe ujian</th>
		<th>tanggal</th>
		<th>waktu</th>
		<th>ruang</th>
		<th>pembimbing 1</th>
		<th>pembimbing 2</th>
		<th>penguji 1</th>
		<th>penguji 2</th>
		<th class='kanan'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("
								SELECT tt_jadwal_sidang.mahasiswa_nim, 
									tm_mahasiswa.mahasiswa_nama, 
									tt_jadwal_sidang.penguji_kode_1, 
									tt_jadwal_sidang.penguji_kode_2, 
									tt_jadwal_sidang.ujian_tipe_id, 
									tt_jadwal_sidang.jadwal_sidang_tanggal, 
									tt_jadwal_sidang.jam_id_awal, 
									tt_jadwal_sidang.jam_id_akhir, 
									tt_jadwal_sidang.ruang_id, 
									tt_tugas_akhir.judul_file_ta1, 
									tt_tugas_akhir.judul_file_ta2, 
									tt_tugas_akhir.pegawai_kode_1 AS pembimbing_kode_1,
									tt_tugas_akhir.pegawai_kode_2 AS pembimbing_kode_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_1)) AS pembimbing_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_2)) AS pembimbing_nama_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai AS tm_pegawai_2
										WHERE      (pegawai_kode =  tt_jadwal_sidang.penguji_kode_2)) AS penguji_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai AS tm_pegawai_1
										WHERE      (pegawai_kode = tt_jadwal_sidang.penguji_kode_2)) AS penguji_nama_2,
									  (SELECT     jam_waktu
										FROM          tm_jam
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_awal)) AS jam_waktu_awal,
									  (SELECT     jam_waktu
										FROM          tm_jam AS tm_jam_1
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_akhir)) AS jam_waktu_akhir,
									  (SELECT     ujian_tipe_nama
										FROM          tm_ujian_tipe
										WHERE      (ujian_tipe_id = tt_jadwal_sidang.ujian_tipe_id)) AS ujian_tipe_nama
								FROM tt_jadwal_sidang 
									INNER JOIN tt_tugas_akhir ON tt_jadwal_sidang.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim 
									INNER JOIN tm_mahasiswa ON tt_jadwal_sidang.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
								WHERE tt_jadwal_sidang.periode_id = '$periode'    
									AND ((tt_tugas_akhir.pegawai_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_tugas_akhir.pegawai_kode_2 = '$_SESSION[pegawai_kode]'))
								ORDER BY tt_jadwal_sidang.jadwal_sidang_tanggal								
								");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_sidang_tanggal']);
		$line = tabel_jadwal($r_jadwal['jadwal_sidang_tanggal'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[mahasiswa_nim]</td>
			<td>$r_jadwal[mahasiswa_nama]</td>
			<td>$r_jadwal[ujian_tipe_nama]</td>
			<td>$tgl</td>
			<td>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td>$r_jadwal[ruang_id]</td>
			<td>$r_jadwal[pembimbing_nama_1] ($r_jadwal[pembimbing_kode_1])</td>
			<td>$r_jadwal[pembimbing_nama_2] ($r_jadwal[pembimbing_kode_2])</td>
			<td>$r_jadwal[penguji_nama_1] ($r_jadwal[penguji_kode_1])</td>
			<td>$r_jadwal[penguji_nama_2] ($r_jadwal[penguji_kode_2])</td>
			<td>";
			if($r_jadwal[ujian_tipe_id]=='ta1')
				echo"<a href='ta/$r_jadwal[judul_file_ta1]' title='Tugas Akhir $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/page_save.png'></a>";
			if($r_jadwal[ujian_tipe_id]=='ta2')
				echo"<a href='ta/$r_jadwal[judul_file_ta2]' title='Tugas Akhir $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/page_save.png'></a>";
			echo"</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='$pdf?periode=$periode' target='_parent' title='Save Jadwal Sidang Mahasiswa Bimbingan'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='$print?periode=$periode' target='_blank' title='Print Jadwal Sidang Mahasiswa Bimbingan'><img src='template/images/printer.png'> <b>Print</b></a>
	</div>
	</div>";
    break;
}
?>