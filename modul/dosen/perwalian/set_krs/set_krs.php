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
                url: "modul/dosen/perwalian/set_krs/jadwal.php",
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
	<h3>Perwalian &#187; Jadwal KRS</h3>
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
		<th width='130px'>periode</th>
		<th width='120px'>kode kelas</th>
		<th width='150px'>tanggal awal</th>
		<th width='150px'>tanggal akhir</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_jadwal=mssql_query("SELECT *
							FROM tt_jadwal_krs
							WHERE periode_id='$periode'
							ORDER BY jadwal_krs_awal ASC
						");
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$awal = tgl_indo($r_jadwal['jadwal_krs_awal']);
		$akhir = tgl_indo($r_jadwal['jadwal_krs_akhir']);
		$line = tabel_antara($r_jadwal['jadwal_krs_awal'],$r_jadwal['jadwal_krs_akhir'],$no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[periode_id]</td>
			<td align='center'>$r_jadwal[kelas_id]</td>
			<td>$awal</td>
			<td>$akhir</td><td><a href='$aksi_self&act=ubah&id=$r_jadwal[jadwal_krs_id]' title='Edit'><img src='template/images/icon_edit.png'></a>
			&nbsp;&nbsp;
				<a href='$aksi?act=hapus&id=$r_jadwal[jadwal_krs_id]' title='Hapus' onClick=\"return confirm('Apakah Anda ingin menghapus jadwal KRS kelas $r_jadwal[kelas_id]?')\"><img src='template/images/icon_delete.png'></a>
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
         
      if (form_data.tanggal_awal.value == ""){
        alert("Anda belum mengisikan Tanggal Awal KRS.");
        form_data.tanggal_awal.focus();
        return (false);
      }     
    
      if (form_data.tanggal_akhir.value == ""){
        alert("Anda belum mengisikan Tanggal Akhir KRS.");
        form_data.tanggal_akhir.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>
    
    <script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/dosen/perwalian/set_krs/kelas_perwalian.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#kelas").html(msg);
                }
            });
          });
        });
    </script>
    
    <?php
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Jadwal KRS &#187; Tambah Jadwal KRS</h3>
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
		<tr>
			<th>Tanggal Awal</th>
			<td>
				<input type='text' id='datepicker' name='tanggal_awal'>
			</td>
		</tr>
		<tr>
			<th>Tanggal Akhir</th>
			<td>
				<input type='text' id='datepicker2' name='tanggal_akhir'>
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
         
      if (form_data.tanggal_awal.value == ""){
        alert("Anda belum mengisikan Tanggal Awal KRS.");
        form_data.tanggal_awal.focus();
        return (false);
      }     
    
      if (form_data.tanggal_akhir.value == ""){
        alert("Anda belum mengisikan Tanggal Akhir KRS.");
        form_data.tanggal_akhir.focus();
        return (false);
      }     
          
      return (true);
    }
    </script>
    
    <script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/dosen/perwalian/set_krs/kelas_perwalian.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#kelas").html(msg);
                }
            });
          });
        });
    </script>
    
    <?php
	$q_data=mssql_query("SELECT tt_jadwal_krs.*, tm_kelas.periode_id AS periode_kelas
						FROM tt_jadwal_krs
							INNER JOIN tm_kelas ON tm_kelas.kelas_id = tt_jadwal_krs.kelas_id 
						WHERE jadwal_krs_id='$_GET[id]'");
	$r_data=mssql_fetch_array($q_data);

	$tanggal_awal=tgl_form($r_data['jadwal_krs_awal']);
	$tanggal_akhir=tgl_form($r_data['jadwal_krs_akhir']);
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Jadwal KRS &#187; Ubah Jadwal KRS</h3>
	<br class='clear'/>
	<form action='$aksi?act=ubah' method='post' id='form_data' onSubmit='return validasi(this)'>
	<input type='hidden' name='id' value='$r_data[jadwal_krs_id]'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr>
			<th width='200px'>Angkatan</th>
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
		<tr>
			<th>Tanggal awal</th>
			<td>
				<input type='text' id='datepicker' name='tanggal_awal' value='$tanggal_awal'>
			</td>
		</tr>
		<tr>
			<th>Tanggal akhir</th>
			<td>
				<input type='text' id='datepicker2' name='tanggal_akhir' value='$tanggal_akhir'>
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