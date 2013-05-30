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
                url: "modul/dosen/perwalian/dft_kelas/kelas.php",
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
	if(substr($periode,-1,1)==1)
	{
		$periode=substr($periode,0,8)."-2";
	}
	elseif(substr($periode,-1,1)==2)
	{
		$periode=(substr($periode,0,4)+1)."-".(substr($periode,5,4)+1)."-1";
	}
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Daftar Kelas</h3>
	<br class='clear'/>
	<b>Tahun Ajaran</b>
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
	<br class='clear'>
	<br class='clear'>
	<div id='kelas'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='100px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='100px'>kode kelas</th>
		<th width='100px'>kode dosen</th>
		<th>nama dosen</th>
		<th>sks</th>
		<th>kuota</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_kelas=mssql_query("
						SELECT DISTINCT tt_jadwal.kelas_id, 
							tt_jadwal.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_sks, 
							tt_jadwal.pegawai_kode, 
							tm_pegawai.pegawai_nama,
							(SELECT tm_ruang.ruang_max
								FROM tm_ruang 
								WHERE tm_ruang.ruang_id = tt_jadwal.ruang_id) AS ruang_max,
							(SELECT jadwal.jadwal_kuota
								FROM tt_jadwal AS jadwal 
								WHERE jadwal.jadwal_id = tt_jadwal.jadwal_id) AS ruang_kuota							
						FROM tt_jadwal 
							INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id 
							INNER JOIN tm_pegawai ON tt_jadwal.pegawai_kode = tm_pegawai.pegawai_kode
						WHERE (tt_jadwal.periode_id = '$periode')
						ORDER BY tt_jadwal.matakuliah_id,
							tt_jadwal.kelas_id							
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$r_kelas[matakuliah_id]</td>
			<td>$r_kelas[matakuliah_nama]</td>
			<td>$r_kelas[kelas_id]</td>
			<td>$r_kelas[pegawai_kode]</td>
			<td>$r_kelas[pegawai_nama]</td>
			<td>$r_kelas[matakuliah_sks]</td>
			<td>$r_kelas[ruang_kuota]/$r_kelas[ruang_max]</td>
			<td><a href='$aksi_self&act=jadwal&periode=$periode&kelas_id=$r_kelas[kelas_id]&matakuliah_id=$r_kelas[matakuliah_id]' title='Detail Jadwal $r_kelas[kelas_id] $r_kelas[matakuliah_nama]'><img src='template/images/detail.ico'></a>
			&nbsp;&nbsp;
				<a href='$aksi_self&act=mahasiswa&periode=$periode&kelas_id=$r_kelas[kelas_id]&matakuliah_id=$r_kelas[matakuliah_id]' title='Detail $r_kelas[kelas_id]'><img src='template/images/chart_bar.png'></a>
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
	
	case "jadwal":
	$q_jadwal=mssql_query("SELECT tt_jadwal.pegawai_kode, 
								tt_jadwal.kelas_id, 
								tt_jadwal.matakuliah_id, 
								(SELECT tm_jam.jam_waktu
									FROM tm_jam 
									WHERE tm_jam.jam_id = tt_jadwal.jam_id) AS jam_waktu_awal, 
							  	tm_hari.hari_nama, 
								tt_jadwal.ruang_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks,
								tm_matakuliah.matakuliah_jam,
								tm_pegawai.pegawai_nama
							FROM tt_jadwal 
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id 
								INNER JOIN tm_pegawai ON tt_jadwal.pegawai_kode = tm_pegawai.pegawai_kode
								INNER JOIN tm_hari ON tm_hari.hari_id = tt_jadwal.hari_id
							WHERE (tt_jadwal.periode_id = '$_GET[periode]') 
								AND (tt_jadwal.matakuliah_id = '$_GET[matakuliah_id]') 
								AND (tt_jadwal.kelas_id = '$_GET[kelas_id]')
							ORDER BY tm_hari.hari_urutan, tt_jadwal.jam_id
						");
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Daftar Kelas Periode $periode &#187; Kelas $_GET[kelas_id] - Mata Kuliah $_GET[matakuliah_id]</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='70px'>kode kelas</th>
		<th width='70px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='70px'>kode dosen</th>
		<th>nama dosen</th>
		<th width='40px'>sks</th>
		<th>jam</th>
		<th>hari</th>
		<th class='kanan' width='60px'>ruang</th>
	</tr>";
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$akhir=$r_jadwal[jam_waktu_awal]+$r_jadwal[matakuliah_jam];
		if($akhir < 10)
		{
			$akhir="0$akhir.00";
		}
		else
		{
			$akhir="$akhir.00";
		}
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$r_jadwal[kelas_id]</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>
			<td align='center'>$r_jadwal[pegawai_kode]</td>
			<td>$r_jadwal[pegawai_nama]</td>
			<td align='center'>$r_jadwal[matakuliah_sks]</td>
			<td align='center'>$r_jadwal[jam_waktu_awal] - $akhir</td>
			<td>$r_jadwal[hari_nama]</td>
			<td align='center'>$r_jadwal[ruang_id]</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;

	case "mahasiswa":
	$q_mhs=mssql_query("SELECT tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama
						FROM tt_nilai 
							INNER JOIN tm_mahasiswa ON tt_nilai.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
						WHERE (tt_nilai.periode_id = '$_GET[periode]') 
							AND (tt_nilai.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_nilai.kelas_id = '$_GET[kelas_id]')
						");
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Daftar Kelas Periode $periode &#187; Mahasiswa Kelas $_GET[kelas_id] - Mata Kuliah $_GET[matakuliah_id]</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='100px'>nim</th>
		<th class='kanan' >nama</th>
	</tr>";
	$no=1;
	while($r_mhs=mssql_fetch_array($q_mhs))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_mhs[mahasiswa_nim]</td>
			<td>$r_mhs[mahasiswa_nama]</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;
}
?>