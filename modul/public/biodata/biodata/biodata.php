<script type="text/javascript">

function propinsi(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'config/fungsi_kota.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById('tampilkota').innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

</script>

<script language="javascript">
function validasi(form_data){
  var tanggal 		= /^[0-9]+\/[0-9]+\/[0-9]{4}$/;
  var kodepos 		= /^[0-9]{5}$/;
  var email 		= /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
  var telp			= /^0[0-9]{8,12}$/;

  if (form_data.pegawai_tmpt_lhr.value == ""){
    alert("Anda belum mengisikan Tempat Lahir.");
    form_data.pegawai_tmpt_lhr.focus();
    return (false);
  }
     
  if (form_data.pegawai_tgl_lhr.value == ""){
    alert("Anda belum mengisikan Tanggal Lahir.");
    form_data.pegawai_tgl_lhr.focus();
    return (false);
  }
     
  if (form_data.pegawai_jln.value == ""){
    alert("Anda belum mengisikan Alamat Jalan.");
    form_data.pegawai_jln.focus();
    return (false);
  }
     
  if (form_data.pegawai_no.value == ""){
    alert("Anda belum mengisikan No. Rumah.");
    form_data.pegawai_no.focus();
    return (false);
  }
     
  if (form_data.pegawai_rt.value == ""){
    alert("Anda belum mengisikan RT Rumah.");
    form_data.pegawai_rt.focus();
    return (false);
  }
     
  if (form_data.pegawai_rw.value == ""){
    alert("Anda belum mengisikan RW Rumah.");
    form_data.pegawai_rw.focus();
    return (false);
  }
     
  if (form_data.pegawai_desa.value == ""){
    alert("Anda belum mengisikan Desa/Kelurahan.");
    form_data.pegawai_desa.focus();
    return (false);
  }
     
  if (form_data.pegawai_kecamatan.value == ""){
    alert("Anda belum mengisikan Kecamatan.");
    form_data.pegawai_kecamatan.focus();
    return (false);
  }
     
  if (form_data.pegawai_kodepos.value == ""){
    alert("Anda belum mengisikan Kode Pos.");
    form_data.pegawai_kodepos.focus();
    return (false);
  }
     
  if (form_data.pegawai_telp.value == ""){
    alert("Anda belum mengisikan No. Telepon/HP.");
    form_data.pegawai_telp.focus();
    return (false);
  }

  if (form_data.pegawai_email.value == ""){
    alert("Anda belum mengisikan Alamat Email.");
    form_data.pegawai_email.focus();
    return (false);
  }
     
   if(form_data.pegawai_tgl_lhr.value.match(tanggal)){
   }else{
		alert("Penulisan Tanggal lahir salah.");
	    form_data.pegawai_tgl_lhr.focus();
		return false;
   }
  
   if(form_data.pegawai_kodepos.value.match(kodepos)){
   }else{
		alert("Kode pos tidak valid.");
	    form_data.pegawai_kodepos.focus();
		return false;
   }
  
   if(form_data.pegawai_telp.value.match(telp)){
   }else{
		alert("Penulisan Telepon/HP salah.");
	    form_data.pegawai_telp.focus();
		return false;
   }
  
   if(form_data.pegawai_email.value.match(email)){
   }else{
		alert("Penulisan Email Salah.");
	    form_data.pegawai_email.focus();
		return false;
   }
   
  return (true);
}
</script>

<script language=Javascript>
<!--
function isNumberKey(evt)
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57))

return false;
return true;
}
//-->
</script>

<script language=Javascript>
<!--
function isAlfabetKey(evt)
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 65 || charCode > 91) && (charCode < 97 || charCode > 122))

return false;
return true;
}
//-->
</script>

<?php
	echo"<div class='content'>
	<h3>Biodata &#187; Biodata Pribadi</h3>
	<br class='clear'/>";
	$q_pegawai=mssql_query("SELECT * FROM tm_pegawai WHERE pegawai_kode='$_SESSION[pegawai_kode]'");
	$r_pegawai=mssql_fetch_array($q_pegawai);
	$tanggal = tgl_form($r_pegawai[pegawai_tgl_lhr]);
	echo "<form id='form_data' action='$aksi?' method='post' onSubmit='return validasi(this)'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<td colspan='2'>";
		if($r_pegawai[pegawai_foto] != '')
			echo "<a id='photo_show' rel='slide' href='images/$r_pegawai[pegawai_foto]' title='$r_pegawai[pegawai_nama] ($r_pegawai[pegawai_kode])'><img src='images/$r_pegawai[pegawai_foto]' width='205px'></a>";
		else
			echo "<img src='images/none.png' width='205px'>";
		echo "</td>
	</tr>
	<tr>
		<th width='140px'>Kode Pegawai</th>
		<td><input type='text' value='$r_pegawai[pegawai_kode]' size='3' disabled></td>
	</tr>
	</tbody>
	<tr>
		<th>Nama</th>
		<td><input type='text' value='$r_pegawai[pegawai_nama]' size='40' disabled></td>
	</tr>
	<tr>
		<th>Jenis Kelamin</th>
		<td>&nbsp;&nbsp;&nbsp;";
		if($r_pegawai[jeniskelamin_id]=='L')
		{
			echo"<input type='radio' value='L' class='radio' disabled checked> L
			<input type='radio' value='P' class='radio' disabled> P";
		}
		else
		{
			echo"<input type='radio' value='L' class='radio' disabled> L
			<input type='radio' value='P' class='radio' disabled checked> P";
		}							
		echo"</td>
	</tr>
	<tr>
		<th>Tempat/Tanggal Lahir</th>
		<td><input type='text' value='$r_pegawai[pegawai_tmpt_lhr]' name='pegawai_tmpt_lhr' size='20' id='text_input1' onkeypress='return isAlfabetKey(event)'/> / <input type='text' value='$tanggal' name='pegawai_tgl_lhr' size='10' id='datepicker'/> &nbsp;&nbsp;&nbsp;dd/mm/yyyy</td>
	</tr>
	<tr>
		<th>Agama</th>
		<td>
			<select class='field select' name='agama_kode'>";
			$agama=mssql_query("select * from tp_agama order by agama_nama");
			while($agm=mssql_fetch_array($agama))
			{
				if($r_pegawai['agama_kode']==$agm['agama_kode'])
				{
					echo"<option value='$agm[agama_kode]' selected>$agm[agama_nama]</option>";	
				}
				else
				{
					echo"<option value='$agm[agama_kode]'>$agm[agama_nama]</option>";	
				}
			}
			echo "</select>
		</td>
	</tr>
	<tr>
		<th>Alamat</th>
		<td>
			&nbsp;&nbsp;&nbsp;Jl. <input type='text' value='$r_pegawai[pegawai_jln]' maxlength='50' size='20' class='field text' name='pegawai_jln' onkeypress='return isAlfabetKey(event)'/> 
			No. <input type='text' value='$r_pegawai[pegawai_no]' maxlength='3' size='1' class='field text' name='pegawai_no' onkeypress='return isNumberKey(event)'/> 
			RT. <input type='text' value='$r_pegawai[pegawai_rt]' maxlength='3' size='1' class='field text' name='pegawai_rt' onkeypress='return isNumberKey(event)'/> 
			RW. <input type='text' value='$r_pegawai[pegawai_rw]' maxlength='3' size='1' class='field text' name='pegawai_rw' onkeypress='return isNumberKey(event)'/> 
			Desa/Kelurahan <input type='text' value='$r_pegawai[pegawai_desa]' maxlength=50' size='20' class='field text' name='pegawai_desa' onkeypress='return isAlfabetKey(event)'/>
			Kecamatan <input type='text' value='$r_pegawai[pegawai_kecamatan]' maxlength=50' size='25' class='field text' name='pegawai_kecamatan' onkeypress='return isAlfabetKey(event)'/>
		</td>
	</tr>
	<tr>
		<th></th>
		<td>
			&nbsp;&nbsp;&nbsp;Propinsi <select class='field select' name='propinsi_id' onChange='javascript:propinsi(this)'>";
			$propinsi = mssql_query("SELECT * FROM tp_propinsi ORDER BY propinsi_nama");
			while($prp=mssql_fetch_array($propinsi))
			{
				if ($r_pegawai[propinsi_id]== $prp[propinsi_id])
				{
					echo "<option value=$prp[propinsi_id] selected>$prp[propinsi_nama]</option>";
				}
				else
				{
					echo "<option value=$prp[propinsi_id]>$prp[propinsi_nama]</option>";
				}
			}
			echo"</select>
			Kota/Kabupaten <select name='kota_kode' id='tampilkota' class='field select'>";						
			$kota = mssql_query("SELECT * FROM tp_kota WHERE propinsi_id='$r_pegawai[propinsi_id]' ORDER BY kota_nama");
			while($kt=mssql_fetch_array($kota))
			{
				if($r_pegawai['kota_kode']==$kt['kota_kode'])
				{
					echo "<option value=$kt[kota_kode] selected>$kt[kota_nama]</option>";
				}
				else
				{
					echo "<option value=$kt[kota_kode]>$kt[kota_nama]</option>";
				}
			}
			echo"</select>						
		</td>
	</tr>
	<tr>
		<th>Kode Pos</th>
		<td><input type='text' value='$r_pegawai[pegawai_kodepos]' name='pegawai_kodepos' size='5' maxlength='5' id='text_input1' onkeypress='return isNumberKey(event)'/> (Kode Pos 5 digit Ex.46725)</td>
	</tr>
	<tr>
		<th>Telepon/HP</th>
		<td><input type='text' value='$r_pegawai[pegawai_telp]' name='pegawai_telp' size='20' id='text_input1' onkeypress='return isNumberKey(event)'/> (Ex. 081326356444)</td>
	</tr>
	<tr>
		<th>Email</th>
		<td><input type='text' value='$r_pegawai[pegawai_email]' name='pegawai_email' size='40' id='text_input1'/> (Ex. joni_andrean@yahoo.com)</td>
	</tr>";
/*	<tr>
		<th>Website</th>
		<td><input type='text' value='$r_pegawai[pegawai_website]' name='pegawai_website' size='40' id='text_input1'/> (Ex. www.politeknik.com)</td>
	</tr>*/
	echo"<tr>
		<th>Golongan Darah</th>
		<td>
			<select class='field select' name='goldarah_id'>";
			$goldarah = mssql_query("SELECT * FROM tp_goldarah ORDER BY goldarah_id");
			while($gdr=mssql_fetch_array($goldarah))
			{
				if ($r_pegawai[goldarah_id]== $gdr[goldarah_id])
				{
					echo "<option value=$gdr[goldarah_id] selected>$gdr[goldarah_id]</option>";
				}
				else
				{
					echo "<option value=$gdr[goldarah_id]>$gdr[goldarah_id]</option>";
				}
			}
			echo"</select>
		</td>
	</tr>
	<tr><th>&nbsp;</th><td align='center'><input type='submit' value='Simpan'></td></tr>
	</table>
	</form>";
	echo "</div>";
	if($_GET[notif] == 'ok')
	{
		echo "<script type='text/javascript'>
			alert('Data Berhasil Disimpan');
		</script>";
	}
?>