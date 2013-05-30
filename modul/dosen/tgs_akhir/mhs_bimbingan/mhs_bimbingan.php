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
                url: "modul/dosen/tgs_akhir/mhs_bimbingan/mahasiswa.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#mahasiswa").html(msg);
                }
            });
          });
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Mahasiswa Bimbingan</h3>
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
	<div id='mahasiswa'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='70px'>nim</th>
		<th width='130px'>nama</th>
		<th width='150px'>pembimbing 1</th>
		<th width='150px'>pembimbing 2</th>
		<th>judul</th>
		<th width='50px'>judul diajukan</th>
		<th width='50px'>judul diterima</th>
		<th width='50px'>judul disahkan</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_bimbingan=mssql_query("
								SELECT tt_tugas_akhir.mahasiswa_nim,
									tt_tugas_akhir.pegawai_kode_1,
									tt_tugas_akhir.pegawai_kode_2,
									tt_tugas_akhir.tugas_akhir_aktif,
									(SELECT tm_pegawai.pegawai_nama
										FROM tm_pegawai
										WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_1) AS pegawai_nama_1,
									(SELECT tm_pegawai.pegawai_nama
										FROM tm_pegawai
										WHERE tm_pegawai.pegawai_kode = tt_tugas_akhir.pegawai_kode_2) AS pegawai_nama_2,
									tt_tugas_akhir.judul_nama,
									tt_tugas_akhir.judul_diajukan,
									tt_tugas_akhir.judul_diterima,
									tt_tugas_akhir.judul_disahkan,
									tt_tugas_akhir.judul_file_ta2,
									tm_mahasiswa.mahasiswa_nama
								FROM tt_tugas_akhir
									INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_tugas_akhir.mahasiswa_nim
								WHERE tt_tugas_akhir.periode_id='$periode'
								AND (tt_tugas_akhir.pegawai_kode_1='$_SESSION[pegawai_kode]' OR tt_tugas_akhir.pegawai_kode_2='$_SESSION[pegawai_kode]')
								");
	while($r_bimbingan=mssql_fetch_array($q_bimbingan))
	{
		$line =tabel_normal($no);
		if($r_bimbingan[tugas_akhir_aktif]=='Y')
			echo $line;
		else
			echo "<tr class='merah'>";
		echo "<td align='center'>$no</td>
			<td align='center'>$r_bimbingan[mahasiswa_nim]</td>
			<td>$r_bimbingan[mahasiswa_nama]</td>
			<td>$r_bimbingan[pegawai_nama_1] ($r_bimbingan[pegawai_kode_1])</td>
			<td>$r_bimbingan[pegawai_nama_2] ($r_bimbingan[pegawai_kode_2])</td>
			<td>$r_bimbingan[judul_nama]</td>
			<td align='center'>";
			if($r_bimbingan[judul_diajukan]=='Y')
			{
				echo"<img src='template/images/icon_accept.png' title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Disetujui' id='foto'>";
			}
			else
			{
				if($r_bimbingan[pegawai_kode_1] == $_SESSION[pegawai_kode] && $r_bimbingan[tugas_akhir_aktif]=='Y' )
				{
					echo"<a href='$aksi?act=setuju&periode=$periode&mahasiswa_nim=$r_bimbingan[mahasiswa_nim]' title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Belum Disetujui'>";
				}
				echo "<img src='template/images/cancel.png'></a>";
			}
			echo"</td>
			<td align='center'>";
			if($r_bimbingan[judul_diterima]=='Y')
			{
				echo"<img src='template/images/icon_accept.png'title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Diterima' id='foto'>";
			}
			else
			{
				echo"<img src='template/images/cancel.png'title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Ditolak' id='foto'>";
			}
			echo"</td>
			<td align='center'>";
			if($r_bimbingan[judul_disahkan]=='Y')
			{
				echo"<img src='template/images/icon_accept.png'title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Diterima' id='foto'>";
			}
			else
			{
				echo"<img src='template/images/cancel.png'title='Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama] Ditolak' id='foto'>";
			}
			echo"</td>
			<td><a href='$aksi_self&act=detailbimbingan&periode=$periode&mahasiswa_nim=$r_bimbingan[mahasiswa_nim]' title='Detail Judul TA $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama]'><img src='template/images/detail.ico'></a>&nbsp;&nbsp;&nbsp;
			<a href='ta/$r_bimbingan[judul_file_ta2]' title='Tugas Akhir $r_bimbingan[mahasiswa_nim] $r_bimbingan[mahasiswa_nama]'><img src='template/images/page_save.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br><br>
	</div>";
    break;

	case "detailbimbingan":
	echo"
	<div class='content'>
	<h3>Tugas Akhir &#187; Mahasiswa Bimbingan Periode $periode &#187; NIM $_GET[mahasiswa_nim]</h3>
	<br class='clear'/>
	<table class='data' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>";
	$q_mhs=mssql_query("
						SELECT tt_tugas_akhir.*,
							tm_mahasiswa.mahasiswa_nama
						FROM tt_tugas_akhir
							INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_tugas_akhir.mahasiswa_nim
						WHERE tt_tugas_akhir.periode_id='$periode'
							AND tt_tugas_akhir.mahasiswa_nim='$_GET[mahasiswa_nim]'
						");
	$r_mhs=mssql_fetch_array($q_mhs);
	echo "<tr><th width='200px'>NIM</th><td>$r_mhs[mahasiswa_nim]</td></tr>
	<tr><th width='200px'>Nama</th><td>$r_mhs[mahasiswa_nama]</td></tr>
	<tr><th width='200px'>Judul Tugas Akhir</th><td>$r_mhs[judul_nama]</td></tr>
	<tr><th width='200px'>Studi Kasus</th><td>$r_mhs[judul_kasus]</td></tr>
	<tr><th width='200px'>Deskripsi</th><td>$r_mhs[judul_deskripsi]</td></tr>
	</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;
}
?>