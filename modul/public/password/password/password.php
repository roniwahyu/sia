<script language="javascript">
function validasi(form_data){
  var password 		= /^[a-z][A-z][0-9]$/;

  if (form_data.pass_lama.value == ""){
    alert("Anda belum mengisikan Password Lama.");
    form_data.pass_lama.focus();
    return (false);
  }
     
  if (form_data.pass_baru.value == ""){
    alert("Anda belum mengisikan Password Baru.");
    form_data.pass_baru.focus();
    return (false);
  }

  if (form_data.pass_baru_konfirm.value == ""){
    alert("Anda belum mengisikan Konfirmasi Password Baru.");
    form_data.pass_baru_konfirm.focus();
    return (false);
  }     
      
  return (true);
}
</script>

<?php
	echo"<div class='content'>
	<h3>Ganti Password &#187; Ganti Password</h3>
	<br class='clear'/>";
	$q_pegawai=mssql_query("SELECT * FROM tm_pegawai WHERE pegawai_kode='$_SESSION[pegawai_kode]'");
	$r_pegawai=mssql_fetch_array($q_pegawai);
	$newdate = date('m/d/Y',strtotime($r_pegawai['pegawai_tgl_lhr']));
	echo "<form method='post' action='$aksi' id='form_data' onSubmit='return validasi(this)'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr>
		<th width='250px'>Password Lama</th>
		<td><input type='password' name='pass_lama' size='40'></td>
	</tr>
	<tr>
		<th>Password Baru</th>
		<td><input type='password' name='pass_baru' size='40'></td>
	</tr>
	<tr>
		<th>Konfirmasi Password Baru</th>
		<td><input type='password' name='pass_baru_konfirm' size='40'></td>
	</tr>
	<tr>
		<th>Password Lama</th>
		<td><input type='submit' value='Simpan' size='40'></td>
	</tr>
	</tbody>
	</table>
	</form>
	</div>";
	if($_GET[notif] == 'ok')
	{
		echo "<script type='text/javascript'>
			alert('Password Berhasil Diganti');
		</script>";
	}
	elseif($_GET[notif] == 'mid')
	{
		echo "<script type='text/javascript'>
			alert('Password Baru dan Konfirmasi Password Baru Tidak Sesuai');
		</script>";
	}
	elseif($_GET[notif] == 'begin')
	{
		echo "<script type='text/javascript'>
			alert('Password Lama Tidak Sesuai');
		</script>";
	}
?>