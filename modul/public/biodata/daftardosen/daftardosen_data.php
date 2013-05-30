<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_tanggal.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/tabel.php";

	$q_pegawai=mssql_query("SELECT tm_pegawai.*,tp_kota.kota_nama,propinsi_nama
							FROM tm_pegawai 
								INNER JOIN tp_propinsi ON tp_propinsi.propinsi_id = tm_pegawai.propinsi_id 
								INNER JOIN tp_kota ON tp_kota.kota_kode = tm_pegawai.kota_kode 
							WHERE tm_pegawai.pegawai_nama LIKE '%$_GET[dosen_nama]%' 
								OR tm_pegawai.pegawai_kode LIKE '%$_GET[dosen_nama]%'
								OR tp_kota.kota_nama LIKE '%$_GET[dosen_nama]%'
							ORDER BY tm_pegawai.pegawai_kode ASC");
	echo "<script type='text/javascript'>	
	$('a#photo_show').fancybox({
		padding: 0, 
		titleShow: false, 
		overlayColor: '#333333', 
		overlayOpacity: .5
	});
	</script>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
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
	</table>";
?>