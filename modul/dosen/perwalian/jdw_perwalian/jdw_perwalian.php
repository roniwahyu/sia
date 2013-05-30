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
            	var angkatan = $("#angkatan").val();
            	var kelas = $("#kelas").val();
            	$.ajax(
				{
                	url: "modul/dosen/perwalian/jdw_perwalian/jadwal_perwalian.php",
                	data: "periode="+periode+"&kelas="+kelas+"&angkatan="+angkatan,
                	cache: false,
                	success: function(msg)
					{
                    	$("#jadwal").html(msg);
                	}
            	});
          	});
    
            $("#angkatan").change(function()
            {
            	var periode = $("#periode").val();
            	var angkatan = $("#angkatan").val();
            	var kelas = $("#kelas").val();
                $.ajax(
                {
                    url: "modul/dosen/perwalian/jdw_perwalian/kelas_view.php",
                    data: "angkatan="+angkatan,
                    cache: false,
                    success: function(msg)
                    {
                        $("#kelas").html(msg);
                    }
                });

            	$.ajax(
				{
                	url: "modul/dosen/perwalian/jdw_perwalian/jadwal_perwalian.php",
                	data: "periode="+periode+"&kelas="+kelas+"&angkatan="+angkatan,
                	cache: false,
                	success: function(msg)
					{
                    	$("#jadwal").html(msg);
                	}
            	});
            });
    
        	$("#kelas").change(function()
			{
            	var periode = $("#periode").val();
            	var angkatan = $("#angkatan").val();
            	var kelas = $("#kelas").val();
            	$.ajax(
				{
                	url: "modul/dosen/perwalian/jdw_perwalian/jadwal_perwalian.php",
                	data: "periode="+periode+"&kelas="+kelas+"&angkatan="+angkatan,
                	cache: false,
                	success: function(msg)
					{
                    	$("#jadwal").html(msg);
                	}
            	});
          	});
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Jadwal Perwalian</h3>
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
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Angkatan</b>
	<select name=periode id='angkatan'>";
		$q_periode=mssql_query("select periode_id from tm_periode where periode_id like '__________1' order by  periode_id desc");
		echo "<option value='%'>SEMUA</option>";
		while($r_periode=mssql_fetch_array($q_periode))
		{
			$angkatan = substr($r_periode[periode_id],0,4);
			echo "<option value='$r_periode[periode_id]'>$angkatan</option>";
		}
	echo "</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Kelas</b>
	<select name='kelas' id='kelas'>
	<option value='%'>SEMUA</option>";
	$q_kelas=mssql_query("
						SELECT tm_kelas.kelas_id, 
							tm_kelas.kelas_nama
						FROM tm_kelas INNER 
							JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
						WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
							AND (tm_kelas.periode_id like '%')							
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		echo "<option value='$r_kelas[kelas_id]'>$r_kelas[kelas_nama]</option>";
	}
	echo "</select>
	<br class='clear'>
	<br class='clear'>
	<div id='jadwal'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='120px'>kode kelas</th>
		<th>tanggal</th>
		<th width='100px'>waktu</th>
		<th width='70px'>ruang</th>
		<th>keterangan</th>
		<th width='50px'>aktif</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT tt_jadwal_perwalian.*,
							(SELECT tm_jam.jam_waktu
							FROM tm_jam
							WHERE tm_jam.jam_id=tt_jadwal_perwalian.jam_id_awal) AS jam_waktu_awal,
							(SELECT tm_jam.jam_waktu
							FROM tm_jam
							WHERE tm_jam.jam_id=tt_jadwal_perwalian.jam_id_akhir) AS jam_waktu_akhir
							FROM tt_jadwal_perwalian
							WHERE periode_id='$periode'
								AND pegawai_kode='$_SESSION[pegawai_kode]'
							ORDER BY jadwal_perwalian_tgl ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_perwalian_tgl']);
		$line = tabel_jadwal($r_jadwal['jadwal_perwalian_tgl'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[kelas_id]</td>
			<td>$tgl</td>
			<td align='center'>$r_jadwal[jam_waktu_awal] - $r_jadwal[jam_waktu_akhir]</td>
			<td align='center'>$r_jadwal[ruang_id]</td>
			<td>$r_jadwal[jadwal_perwalian_ket]</td>";
			if($r_jadwal[jadwal_perwalian_aktif]=='Y')
				echo "<td align='center'><a href='$aksi?act=nonaktif&id=$r_jadwal[jadwal_perwalian_id]' title='Aktif'><img src='template/images/icon_accept.png'></a></td>";
			else
				echo "<td align='center'><a href='$aksi?act=aktif&id=$r_jadwal[jadwal_perwalian_id]' title='Non Aktif'><img src='template/images/cancel.png'></a></td>";
			echo "<td><a href='$aksi_self&act=ubah&id=$r_jadwal[jadwal_perwalian_id]' title='Edit'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='$aksi?act=hapus&id=$r_jadwal[jadwal_perwalian_id]' title='Hapus' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal Perwalian kelas $r_jadwal[kelas_id]?')\"><img src='template/images/icon_delete.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br><br>
	<a href='$aksi_self&act=tambah' title='Tambah Jadwal Perwalian'><img src='template/images/add.png'> <b>Tambah</b></a>
	</div>";
    break;
	
	case "tambah":
	?>
	<script language="javascript">
    function validasi(form_data){
      if (form_data.periode.value == ""){
        alert("Anda Memilih belum Angkatan.");
        form_data.periode.focus();
        return (false);
      }
    
      if (form_data.kelas.value == ""){
        alert("Anda belum Memilih Kelas.");
        form_data.kelas.focus();
        return (false);
      }
         
      if (form_data.keterangan.value == ""){
        alert("Anda belum mengisikan Keterangan.");
        form_data.keterangan.focus();
        return (false);
      }
    
      if (form_data.tanggal.value == ""){
        alert("Anda belum mengisikan Tanggal Perwalian.");
        form_data.tanggal.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>

	<script type="text/javascript">
        var htmlobjek;
    
        $(document).ready(function()
        {
            $("#periode").change(function()
            {
                var periode = $("#periode").val();
                $.ajax(
                {
                    url: "modul/dosen/perwalian/jdw_perwalian/kelas_perwalian.php",
                    data: "periode="+periode,
                    cache: false,
                    success: function(msg)
                    {
                        $("#kelas").html(msg);
                    }
                });
            });
    
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Jadwal perwalian &#187; Tambah Jadwal Perwalian</h3>
	<br class='clear'/>
	<form action='$aksi?act=tambah' method='post' id='form_data' onSubmit='return validasi(this)'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr>
			<th width='200px'>Angkatan</th>
			<td>
				<select name=periode id='periode'>";
					$q_periode=mssql_query("select periode_id from tm_periode where periode_id like '__________1' and periode_aktif <> 'N' order by  periode_id desc");
					$periode = substr($periode,0,4);
					while($r_periode=mssql_fetch_array($q_periode))
					{
						$angkatan=substr($r_periode[periode_id],0,4);
						if($angkatan == $periode)
							echo "<option value='$r_periode[periode_id]' selected='selected'>$angkatan</option>";
						else
							echo "<option value='$r_periode[periode_id]'>$angkatan</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>Kelas</th>
			<td>
				<select name=kelas id='kelas'>";
					$q_kelas=mssql_query("
										SELECT tm_kelas.kelas_id, 
											tm_kelas.kelas_nama
										FROM tm_kelas INNER 
											JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
										WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
											AND (tm_kelas.periode_id like '$periode%')							
											");
					while($r_kelas=mssql_fetch_array($q_kelas))
					{
						echo "<option value='$r_kelas[kelas_id]'>$r_kelas[kelas_nama]</option>";
					}
				echo "</select>
			</td>
		</tr>
			<th>Keterangan</th>
			<td>
				<textarea name='keterangan' rows='8' cols='40'></textarea>
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
		</tr>
			<th>Aktif</th>
			<td>
				<input type='radio' name='aktif' value='Y' checked='checked'>&nbsp;<b>Y</b>
				<input type='radio' name='aktif' value='N'>&nbsp;<b>N</b>
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
      if (form_data.periode.value == ""){
        alert("Anda Memilih belum Angkatan.");
        form_data.periode.focus();
        return (false);
      }
    
      if (form_data.kelas.value == ""){
        alert("Anda belum Memilih Kelas.");
        form_data.kelas.focus();
        return (false);
      }
         
      if (form_data.keterangan.value == ""){
        alert("Anda belum mengisikan Keterangan.");
        form_data.keterangan.focus();
        return (false);
      }
    
      if (form_data.tanggal.value == ""){
        alert("Anda belum mengisikan Tanggal Perwalian.");
        form_data.tanggal.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>

	<script type="text/javascript">
        var htmlobjek;
    
        $(document).ready(function()
        {
            $("#periode").change(function()
            {
                var periode = $("#periode").val();
                $.ajax(
                {
                    url: "modul/dosen/perwalian/jdw_perwalian/kelas_perwalian.php",
                    data: "periode="+periode,
                    cache: false,
                    success: function(msg)
                    {
                        $("#kelas").html(msg);
                    }
                });
            });
    
        });
    </script>
    <?php
	$q_data=mssql_query("SELECT tt_jadwal_perwalian.*, tm_kelas.periode_id AS periode_kelas
						FROM tt_jadwal_perwalian
							INNER JOIN tm_kelas ON tm_kelas.kelas_id = tt_jadwal_perwalian.kelas_id 
						WHERE jadwal_perwalian_id='$_GET[id]'");
	$r_data=mssql_fetch_array($q_data);
	$tanggal = tgl_form($r_data['jadwal_perwalian_tgl']);
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Jadwal Perwalian &#187; Ubah Jadwal Perwalian</h3>
	<br class='clear'/>
	<form action='$aksi?act=ubah' method='post' id='form_data' onSubmit='return validasi(this)'>
	<input type='hidden' name='id' value='$r_data[jadwal_perwalian_id]'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr>
			<th width='200px'>Periode</th>
			<td>
				<select name=periode id='periode'>";
					$q_periode=mssql_query("select periode_id from tm_periode where periode_id like '__________1' and periode_aktif <> 'N' order by  periode_id desc");
					while($r_periode=mssql_fetch_array($q_periode))
					{
						$angkatan = substr($r_periode[periode_id],0,4);
						if($r_periode[periode_id] == $r_data[periode_kelas])
							echo "<option value='$r_periode[periode_id]' selected='selected'>$angkatan</option>";
						else
							echo "<option value='$r_periode[periode_id]'>$angkatan</option>";
					}
				echo "</select>
			</td>
		</tr>
		<tr>
			<th>Kelas</th>
			<td>
				<select name=kelas id='kelas'>";
					$q_kelas=mssql_query("
										SELECT tm_kelas.kelas_id, 
											tm_kelas.kelas_nama
										FROM tm_kelas INNER 
											JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
										WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
											AND (tm_kelas.periode_id = '$r_data[periode_kelas]')							
											");
					while($r_kelas=mssql_fetch_array($q_kelas))
					{
						if($r_kelas[kelas_id] == $r_data[kelas_id])
							echo "<option value='$r_kelas[kelas_id]' selected='selected'>$r_kelas[kelas_nama]</option>";
						else
							echo "<option value='$r_kelas[kelas_id]'>$r_kelas[kelas_nama]</option>";
					}
				echo "</select>
			</td>
		</tr>
			<th>Keterangan</th>
			<td>
				<textarea name='keterangan' rows='8' cols='40'>$r_data[jadwal_perwalian_ket]</textarea>
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
		</tr>
			<th>Aktif</th>
			<td>";
				if($r_data[jadwal_perwalian_aktif]=='Y')
					echo "<input type='radio' name='aktif' value='Y' checked='checked'>&nbsp;<b>Y</b>
					<input type='radio' name='aktif' value='N'>&nbsp;<b>N</b>";
				else
					echo "<input type='radio' name='aktif' value='Y'>&nbsp;<b>Y</b>
					<input type='radio' name='aktif' value='N' checked='checked'>&nbsp;<b>N</b>";
			echo "</td>
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