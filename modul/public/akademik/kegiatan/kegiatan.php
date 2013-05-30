<link rel='stylesheet' type='text/css' href='template/css/cupertino/theme.css' />
<link rel='stylesheet' type='text/css' href='template/css/fullcalender/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='template/css/fullcalender/fullcalendar.print.css' media='print' />
<script type="text/javascript" src="template/js/jquery-ui_new.js"></script>
<script type='text/javascript' src='template/js/fullcalender/fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#fullcalendar').fullCalendar({
			theme: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			events: [
			<?php
				//Kalender Akademik
				$q_kalender=mssql_query("
										SELECT tt_kalender_akademik.kalender_akademik_awal, 
											tt_kalender_akademik.kalender_akademik_akhir, 
											tm_keterangan_kalender.keterangan_kalender_nama
										FROM tt_kalender_akademik 
											INNER JOIN tm_keterangan_kalender ON tt_kalender_akademik.keterangan_kalender_id = tm_keterangan_kalender.keterangan_kalender_id
										WHERE (tt_kalender_akademik.periode_id = '$periode')
										ORDER BY tt_kalender_akademik.kalender_akademik_awal");
				while($r_kalender=mssql_fetch_array($q_kalender))
				{
					$tanggal_awal=tgl_format($r_kalender[kalender_akademik_awal]);
					$tanggal_akhir=tgl_format($r_kalender[kalender_akademik_akhir]);
					$thn_awal=substr($tanggal_awal,0,4);
					$bln_awal=substr($tanggal_awal,5,2)-1;
					$tgl_awal=substr($tanggal_awal,8,2);

					$thn_akhir=substr($tanggal_akhir,0,4);
					$bln_akhir=substr($tanggal_akhir,5,2)-1;
					$tgl_akhir=substr($tanggal_akhir,8,2);
					echo "{
						title: '$r_kalender[keterangan_kalender_nama]',
						start: new Date($thn_awal, $bln_awal, $tgl_awal),
						end: new Date($thn_akhir, $bln_akhir, $tgl_akhir),
						allDay: true
					},";
				}
				
				//Jadwal Bimbingan Tugas Akhir
				$q_jdw_bimbingan_ta=mssql_query("SELECT tt_jadwal_bimbingan.*,tm_mahasiswa.mahasiswa_nama
										FROM tt_jadwal_bimbingan
											INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_jadwal_bimbingan.mahasiswa_nim
											INNER JOIN tt_tugas_akhir ON tm_mahasiswa.mahasiswa_nim=tt_tugas_akhir.mahasiswa_nim
										WHERE tt_jadwal_bimbingan.periode_id='$periode'
											AND tt_jadwal_bimbingan.pegawai_kode='$_SESSION[pegawai_kode]'
										ORDER BY tt_jadwal_bimbingan.jadwal_bimbingan_tanggal ASC
									");
				while($r_jdw_bimbingan_ta=mssql_fetch_array($q_jdw_bimbingan_ta))
				{
					$tanggal=tgl_format($r_jdw_bimbingan_ta[jadwal_bimbingan_tanggal]);
					$thn=substr($tanggal,0,4);
					$bln=substr($tanggal,5,2)-1;
					$tgl=substr($tanggal,8,2);
					echo "{
						title: 'Bimbingan Tugas Akhir $r_jdw_bimbingan_ta[mahasiswa_nim] $r_jdw_bimbingan_ta[mahasiswa_nama]',
						start: new Date($thn, $bln, $tgl, $r_jdw_bimbingan_ta[jam_id_awal]+7, 0),
						end: new Date($thn, $bln, $tgl, $r_jdw_bimbingan_ta[jam_id_akhir]+7, 0),
						allDay: false
					},";
				}
				
				//Jadwal Sidang Mahasiswa Bimbingan
				$q_jadwal_sidang=mssql_query("
											SELECT tt_jadwal_sidang.mahasiswa_nim, 
												tm_mahasiswa.mahasiswa_nama, 
												tt_jadwal_sidang.jadwal_sidang_tanggal, 
												tt_jadwal_sidang.jam_id_awal, 
												tt_jadwal_sidang.jam_id_akhir, 
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
				while($r_jadwal_sidang=mssql_fetch_array($q_jadwal_sidang))
				{
					$tanggal=tgl_format($r_jadwal_sidang[jadwal_sidang_tanggal]);
					$thn=substr($tanggal,0,4);
					$bln=substr($tanggal,5,2)-1;
					$tgl=substr($tanggal,8,2);
					echo "{
						title: '$r_jadwal_sidang[ujian_tipe_nama] $r_jadwal_sidang[mahasiswa_nim] $r_jadwal_sidang[mahasiswa_nama] (Pembimbing)',
						start: new Date($thn, $bln, $tgl, $r_jadwal_sidang[jam_id_awal]+7, 0),
						end: new Date($thn, $bln, $tgl, $r_jadwal_sidang[jam_id_akhir]+7, 0),
						allDay: false
					},";
				}

				//Jadwal Penguji
				$q_jadwal_sidang=mssql_query("
											SELECT tt_jadwal_sidang.mahasiswa_nim, 
												tm_mahasiswa.mahasiswa_nama, 
												tt_jadwal_sidang.jadwal_sidang_tanggal, 
												tt_jadwal_sidang.jam_id_awal, 
												tt_jadwal_sidang.jam_id_akhir, 
											  (SELECT     ujian_tipe_nama
												FROM          tm_ujian_tipe
												WHERE      (ujian_tipe_id = tt_jadwal_sidang.ujian_tipe_id)) AS ujian_tipe_nama
											FROM tt_jadwal_sidang 
												INNER JOIN tm_mahasiswa ON tt_jadwal_sidang.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
											WHERE tt_jadwal_sidang.periode_id = '$periode'    
												AND ((tt_jadwal_sidang.penguji_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_jadwal_sidang.penguji_kode_2 = '$_SESSION[pegawai_kode]'))
											ORDER BY tt_jadwal_sidang.jadwal_sidang_tanggal								
											");
				while($r_jadwal_sidang=mssql_fetch_array($q_jadwal_sidang))
				{
					$tanggal=tgl_format($r_jadwal_sidang[jadwal_sidang_tanggal]);
					$thn=substr($tanggal,0,4);
					$bln=substr($tanggal,5,2)-1;
					$tgl=substr($tanggal,8,2);
					echo "{
						title: '$r_jadwal_sidang[ujian_tipe_nama] $r_jadwal_sidang[mahasiswa_nim] $r_jadwal_sidang[mahasiswa_nama] (Penguji)',
						start: new Date($thn, $bln, $tgl, $r_jadwal_sidang[jam_id_awal]+7, 0),
						end: new Date($thn, $bln, $tgl, $r_jadwal_sidang[jam_id_akhir]+7, 0),
						allDay: false
					},";
				}
				
				//Jadwal Perwalian
				$q_jadwal_perwalian=mssql_query("SELECT tt_jadwal_perwalian.*
										FROM tt_jadwal_perwalian
										WHERE periode_id='$periode'
											AND pegawai_kode='$_SESSION[pegawai_kode]'
										ORDER BY jadwal_perwalian_tgl ASC
									");
				while($r_jadwal_perwalian=mssql_fetch_array($q_jadwal_perwalian))
				{
					$tanggal=tgl_format($r_jadwal_perwalian[jadwal_perwalian_tgl]);
					$thn=substr($tanggal,0,4);
					$bln=substr($tanggal,5,2)-1;
					$tgl=substr($tanggal,8,2);
					echo "{
						title: 'Perwalian Kelas $r_jadwal_perwalian[kelas_id]',
						start: new Date($thn, $bln, $tgl, $r_jadwal_perwalian[jam_id_awal]+7, 0),
						end: new Date($thn, $bln, $tgl, $r_jadwal_perwalian[jam_id_akhir]+7, 0),
						allDay: false
					},";
				}
			?>
				{
					title: 'Mahendri Winata',
					start: new Date(1989, 11, 1)
				}
			]
		});
		
	});

</script>
<div class='content'>
	<h3>Akademik &#187; Agenda Periode <?php echo $periode;?></h3>
    <br class='clear'/>
    <div id='fullcalendar'></div>
    <br class='clear'/>
    <br class='clear'/>
</div>