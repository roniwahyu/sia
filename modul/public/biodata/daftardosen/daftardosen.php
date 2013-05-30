<script type="text/javascript">
function dosen()
{
	var htmlobjek;
    var dosen_nama = $("#dosen_nama").val();
	$.ajax(
	{
		url: "modul/public/biodata/daftardosen/daftardosen_data.php",
		data: "dosen_nama="+dosen_nama,
		cache: false,
		success: function(msg)
		{
			$("#dosen").html(msg);
		}
	});
}
</script>
<?php
	echo"<div class='content'>
	<h3>Biodata &#187; Daftar Dosen</h3>
	<br class='clear'/>
	<b>Cari Dosen</b><input type='text' onkeyup='javascript:dosen(this)' id='dosen_nama'>
	<br class='clear'>
	<br class='clear'>
	<div id='dosen'>";
	$q_pegawai=mssql_query("SELECT tm_pegawai.*,tp_kota.kota_nama,propinsi_nama
							FROM tm_pegawai 
								INNER JOIN tp_propinsi ON tp_propinsi.propinsi_id = tm_pegawai.propinsi_id 
								INNER JOIN tp_kota ON tp_kota.kota_kode = tm_pegawai.kota_kode 
							ORDER BY pegawai_kode ASC");
	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no.</th>
		<th>pegawai kode</th>
		<th>nama</th>
		<th>jenis kelamin</th>
		<th>tempat/tanggal lahir</td>
		<th>alamat</th>
		<th>no. telepon</th>
		<th class='kanan'>email</th>
	</tr>";
	$no=1;
	while($r_pegawai=mssql_fetch_array($q_pegawai))
	{
		$tanggal = tgl_form($r_pegawai['pegawai_tgl_lhr']);
		$line =tabel_normal($no);
			echo "$line<td>$no</td>";
			if($r_pegawai[pegawai_foto] != "")
			{
				echo "<td><a id='photo_show' rel='slide' href='images/$r_pegawai[pegawai_foto]' title='$r_pegawai[pegawai_nama] $r_pegawai[pegawai_kode]'>$r_pegawai[pegawai_kode]</a></td>";
			}
			else
			{
				echo "<td><a id='photo_show' rel='slide' href='images/none.png' title='$r_pegawai[pegawai_nama] $r_pegawai[pegawai_kode]'>$r_pegawai[pegawai_kode]</a></td>";
			}
			echo "<td>$r_pegawai[pegawai_nama]</td>
			<td>$r_pegawai[jeniskelamin_id]</td>
			<td>$r_pegawai[pegawai_tmpt_lhr], $tanggal</td>
			<td>Jl. $r_pegawai[pegawai_jln] No. $r_pegawai[pegawai_no] RT. $r_pegawai[pegawai_rt]/$r_pegawai[pegawai_rw] Ds. $r_pegawai[pegawai_desa] Kec. $r_pegawai[pegawai_kecamatan] $r_pegawai[kota_nama] $r_pegawai[propinsi_nama] $r_pegawai[pegawai_kodepos]</td>
			<td>$r_pegawai[pegawai_telp]</td>
			<td>$r_pegawai[pegawai_email]</td>
		</tr>";
		$no++;
	}
	echo"</tbody>
	</table>
	</div>
	</div>
	";
?>