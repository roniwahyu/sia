<?php
switch($_GET[act]){
  // Tampil jurusan Utama
	default:
	?>
	<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function()
		{
        	$("#periode").change(function()
			{
            	var periode = $("#periode").val();
            	var mahasiswa = $("#mahasiswa").val();
				$.ajax({
					url: "modul/dosen/tgs_akhir/jdw_bimbingan/mahasiswa_view.php",
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
					url: "modul/dosen/tgs_akhir/jdw_bimbingan/jdw_bimbingan_data.php",
					data: "periode="+periode+"&mahasiswa="+mahasiswa,
					cache: false,
					success: function(msg)
					{
						$("#jdw_bimbingan").html(msg);
                	}
            	});				
			});

        	$("#mahasiswa").change(function()
			{
            	var periode = $("#periode").val();
            	var mahasiswa = $("#mahasiswa").val();
            	$.ajax(
				{
					url: "modul/dosen/tgs_akhir/jdw_bimbingan/jdw_bimbingan_data.php",
					data: "periode="+periode+"&mahasiswa="+mahasiswa,
					cache: false,
					success: function(msg)
					{
						$("#jdw_bimbingan").html(msg);
                	}
            	});				
			});

        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Jadwal Bimbingan</h3>
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
	<div id='jdw_bimbingan'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='70px'>nim</th>
		<th width='130px'>nama</th>
		<th width='130px'>pembimbing 1</th>
		<th width='130px'>pembimbing 2</th>
		<th>catatan</th>
		<th width='100px'>tanggal</th>
		<th width='50px'>ruang</th>
		<th width='90px'>waktu</th>
		<th width='60px' class='kanan'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT tt_jadwal_bimbingan.*,
								tt_tugas_akhir.pegawai_kode_1,
								tt_tugas_akhir.pegawai_kode_2,
								(SELECT tm_pegawai.pegawai_nama
									FROM tm_pegawai
									WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_1) AS pegawai_nama_1,
								(SELECT tm_pegawai.pegawai_nama
									FROM tm_pegawai
									WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_2) AS pegawai_nama_2,
								tm_mahasiswa.mahasiswa_nama,
								(SELECT tm_jam.jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_bimbingan.jam_id_awal) AS jam_waktu_awal,
								(SELECT tm_jam.jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_bimbingan.jam_id_akhir) AS jam_waktu_akhir								
							FROM tt_jadwal_bimbingan
								INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_jadwal_bimbingan.mahasiswa_nim
								INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim=tt_tugas_akhir.mahasiswa_nim
							WHERE tt_jadwal_bimbingan.periode_id='$periode'
								AND tt_jadwal_bimbingan.pegawai_kode='$_SESSION[pegawai_kode]'
							ORDER BY tt_jadwal_bimbingan.jadwal_bimbingan_tanggal ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_bimbingan_tanggal']);
		$line = tabel_jadwal($r_jadwal['jadwal_bimbingan_tanggal'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[mahasiswa_nim]</td>
			<td>$r_jadwal[mahasiswa_nama]</td>
			<td>$r_jadwal[pegawai_nama_1] ($r_jadwal[pegawai_kode_1])</td>
			<td>$r_jadwal[pegawai_nama_2] ($r_jadwal[pegawai_kode_2])</td>
			<td>$r_jadwal[jadwal_bimbingan_catatan]</td>
			<td>$tgl</td>
			<td align='center'>$r_jadwal[ruang_id]</td>
			<td align='center'>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td><a href='$aksi_self&act=ubah&id=$r_jadwal[jadwal_bimbingan_id]' title='Edit jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='$aksi?act=hapus&id=$r_jadwal[jadwal_bimbingan_id]' title='Hapus  jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal Bimbingan kelas $r_jadwal[mahasiswa_nim] $r_jadwal[mahasiswa_nama]?')\"><img src='template/images/icon_delete.png'></a></td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br><br>
	<a href='$aksi_self&act=tambah' title='Tambah Jadwal Bimbingan'><img src='template/images/add.png'> <b>Tambah</b></a>
	</div>";
    break;
	
	case "tambah":
	?>
	<script language="javascript">
    function validasi(form_data){
      if (form_data.mahasiswa.value == ""){
        alert("Anda belum Memilih Mahasiswa.");
        form_data.mahasiswa.focus();
        return (false);
      }
         
      if (form_data.catatan.value == ""){
        alert("Anda belum mengisikan Catatan.");
        form_data.catatan.focus();
        return (false);
      }
    
      if (form_data.tanggal.value == ""){
        alert("Anda belum mengisikan Tanggal Bimbingan.");
        form_data.tanggal.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>
    
    <script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          //apabila terjadi event onchange terhadap object <select id=propinsi>
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/dosen/tgs_akhir/jdw_bimbingan/mahasiswa_bimbingan.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    //jika data sukses diambil dari server kita tampilkan
                    //di <select id=kota>
                    $("#mahasiswa").html(msg);
                }
            });
          });
        });
    </script>

    <?php
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Jadwal Bimbingan &#187; Tambah Jadwal Bimbingan</h3>
	<br class='clear'/>
	<form action='$aksi?act=tambah' method='post' id='form_data' onSubmit='return validasi(this)'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr>
			<th width='200px'>Periode</th>
			<td>
				<select name=periode id='periode'>";
					$q_periode=mssql_query("select periode_id from tm_periode order by  periode_id desc");
					while($r_periode=mssql_fetch_array($q_periode))
					{
						if($r_periode[periode_id] == $periode)
							echo "<option value='$r_periode[periode_id]' selected='selected'>$r_periode[periode_id]</option>";
						else
							echo "<option value='$r_periode[periode_id]'>$r_periode[periode_id]</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>NIM / Nama</th>
			<td>
				<select name=mahasiswa_nim id='mahasiswa'>";
					$q_mhs=mssql_query("
										SELECT tm_mahasiswa.mahasiswa_nim, 
										tm_mahasiswa.mahasiswa_nama
										FROM tm_mahasiswa 
											INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim AND tt_tugas_akhir.periode_id = '$periode'
										WHERE ((tt_tugas_akhir.pegawai_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_tugas_akhir.pegawai_kode_2 = '$_SESSION[pegawai_kode]'))
											AND tt_tugas_akhir.tugas_akhir_aktif='Y'
										ORDER BY tm_mahasiswa.mahasiswa_nim 
									");
					while($r_mhs=mssql_fetch_array($q_mhs))
					{
						echo "<option value='$r_mhs[mahasiswa_nim]'>$r_mhs[mahasiswa_nim]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$r_mhs[mahasiswa_nama]</option>";
					}
				echo "</select>
			</td>
		</tr>
			<th>Catatan Bimbingan</th>
			<td>
				<textarea name='catatan' rows='8' cols='40'></textarea>
			</td>
		</tr>
		</tr>
			<th>Tanggal</th>
			<td>
				<input type='text' id='datepicker' name='tanggal'>
			</td>
		</tr>
		</tr>
			<th>Waktu</th>
			<td>
				<select name=jam_id_awal>";
					$q_jam=mssql_query("
											SELECT jam_id, 
												jam_waktu 
											FROM tm_jam  
											");
					while($r_jam=mssql_fetch_array($q_jam))
					{
						echo "<option value='$r_jam[jam_id]'>$r_jam[jam_waktu]</option>";
					}
				echo "</select> - 
				<select name=jam_id_akhir>";
					$q_jam=mssql_query("
											SELECT jam_id, 
												jam_waktu 
											FROM tm_jam  
											");
					while($r_jam=mssql_fetch_array($q_jam))
					{
						echo "<option value='$r_jam[jam_id]'>$r_jam[jam_waktu]</option>";
					}
				echo "</select>
			</td>
		</tr>
		</tr>
			<th>Ruang</th>
			<td>
				<select name=ruang_id>";
					$q_ruang=mssql_query("
											SELECT ruang_id 
											FROM tm_ruang  
											");
					while($r_ruang=mssql_fetch_array($q_ruang))
					{
						echo "<option value='$r_ruang[ruang_id]'>$r_ruang[ruang_id]</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th><td><input type='submit' Value='Simpan'></td>
		</tr>	
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;

	case "ubah":
	?>
	<script language="javascript">
    function validasi(form_data){
      if (form_data.mahasiswa.value == ""){
        alert("Anda belum Memilih Mahasiswa.");
        form_data.mahasiswa.focus();
        return (false);
      }
         
      if (form_data.catatan.value == ""){
        alert("Anda belum mengisikan Catatan.");
        form_data.catatan.focus();
        return (false);
      }
    
      if (form_data.tanggal.value == ""){
        alert("Anda belum mengisikan Tanggal Bimbingan.");
        form_data.tanggal.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>
    
    <script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          //apabila terjadi event onchange terhadap object <select id=propinsi>
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/dosen/tgs_akhir/jdw_bimbingan/mahasiswa_bimbingan.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    //jika data sukses diambil dari server kita tampilkan
                    //di <select id=kota>
                    $("#mahasiswa").html(msg);
                }
            });
          });
        });
    </script>

    <?php
	$q_data=mssql_query("SELECT * FROM tt_jadwal_bimbingan WHERE jadwal_bimbingan_id='$_GET[id]'");
	$r_data=mssql_fetch_array($q_data);
	$tanggal = tgl_form($r_data['jadwal_bimbingan_tanggal']);
	
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Jadwal Bimbingan &#187; Tambah Jadwal Bimbingan</h3>
	<br class='clear'/>
	<form action='$aksi?act=ubah' method='post' id='form_data' onSubmit='return validasi(this)'>
	<input type='hidden' name='id' value='$r_data[jadwal_bimbingan_id]'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr>
			<th width='200px'>Periode</th>
			<td>
				<select name=periode id='periode'>";
					$q_periode=mssql_query("select periode_id from tm_periode order by  periode_id desc");
					while($r_periode=mssql_fetch_array($q_periode))
					{
						if($r_periode[periode_id] == $r_data[periode_id])
							echo "<option value='$r_periode[periode_id]' selected='selected'>$r_periode[periode_id]</option>";
						else
							echo "<option value='$r_periode[periode_id]'>$r_periode[periode_id]</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>NIM / Nama</th>
			<td>
				<select name=mahasiswa_nim id='mahasiswa'>";
					$q_mhs=mssql_query("
										SELECT tm_mahasiswa.mahasiswa_nim, 
										tm_mahasiswa.mahasiswa_nama
										FROM tm_mahasiswa 
											INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim AND tt_tugas_akhir.periode_id = '$r_data[periode_id]'
										WHERE ((tt_tugas_akhir.pegawai_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_tugas_akhir.pegawai_kode_2 = '$_SESSION[pegawai_kode]'))
											AND tt_tugas_akhir.tugas_akhir_aktif='Y'
										ORDER BY tm_mahasiswa.mahasiswa_nim 
									");
					while($r_mhs=mssql_fetch_array($q_mhs))
					{
						if($r_mhs[mahasiswa_nim] == $r_data[mahasiswa_nim])
							echo "<option value='$r_mhs[mahasiswa_nim]' selected='selected'>$r_mhs[mahasiswa_nim]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$r_mhs[mahasiswa_nama]</option>";
						else
							echo "<option value='$r_mhs[mahasiswa_nim]'>$r_mhs[mahasiswa_nim]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$r_mhs[mahasiswa_nama]</option>";
					}
				echo "</select>
			</td>
		</tr>
			<th>Catatan Bimbingan</th>
			<td>
				<textarea name='catatan' rows='8' cols='40'>$r_data[jadwal_bimbingan_catatan]</textarea>
			</td>
		</tr>
		</tr>
			<th>Tanggal</th>
			<td>
				<input type='text' id='datepicker' name='tanggal' value='$tanggal'>
			</td>
		</tr>
		</tr>
			<th>Waktu</th>
			<td>
				<select name=jam_id_awal>";
					$q_jam=mssql_query("
											SELECT jam_id, 
												jam_waktu 
											FROM tm_jam  
											");
					while($r_jam=mssql_fetch_array($q_jam))
					{
						if($r_data[jam_id_awal]==$r_jam[jam_id])
							echo "<option value='$r_jam[jam_id]' selected='selected'>$r_jam[jam_waktu]</option>";
						else
							echo "<option value='$r_jam[jam_id]'>$r_jam[jam_waktu]</option>";
					}
				echo "</select> - 
				<select name=jam_id_akhir>";
					$q_jam=mssql_query("
											SELECT jam_id, 
												jam_waktu 
											FROM tm_jam  
											");
					while($r_jam=mssql_fetch_array($q_jam))
					{
						if($r_data[jam_id_akhir]==$r_jam[jam_id])
							echo "<option value='$r_jam[jam_id]' selected='selected'>$r_jam[jam_waktu]</option>";
						else
							echo "<option value='$r_jam[jam_id]'>$r_jam[jam_waktu]</option>";
					}
				echo "</select>
			</td>
		</tr>
		</tr>
			<th>Ruang</th>
			<td>
				<select name=ruang_id>";
					$q_ruang=mssql_query("
											SELECT ruang_id 
											FROM tm_ruang  
											");
					while($r_ruang=mssql_fetch_array($q_ruang))
					{
						if($r_ruang[ruang_id] == $r_data[ruang_id])
							echo "<option value='$r_ruang[ruang_id]' selected='selected'>$r_ruang[ruang_id]</option>";
						else
							echo "<option value='$r_ruang[ruang_id]'>$r_ruang[ruang_id]</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th><td><input type='submit' Value='Simpan'></td>
		</tr>	
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;
}
?>