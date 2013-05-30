	<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/public/akademik/kalender/kalender_data.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#kalender").html(msg);
                }
            });
          });
        });
    </script>

<?php
	echo"
	<div class='content'>
	<h3>Akademik &#187; Kalender Akademik</h3>
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
	<div id='kalender'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th>Agenda Kegiatan</th>
		<th width='200px'>tanggal awal</th>
		<th width='200px' class='kanan'>tanggal akhir</th>
	</tr>";
	$no=1;
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
		$awal = tgl_indo($r_kalender[kalender_akademik_awal]);
		$akhir = tgl_indo($r_kalender[kalender_akademik_akhir]);
		$line = tabel_antara($r_kalender[kalender_akademik_awal],$r_kalender[kalender_akademik_akhir],$no);
		echo "$line<td align='center'>$no</td>
		<td>$r_kalender[keterangan_kalender_nama]</td>
		<td>$awal</td>
		<td>$akhir</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	<a href='$pdf?periode=$periode' target='_parent' title='Save Kalender Akademik'><img src='template/images/page_save.png'> <b>Save</b></a>
	&nbsp;&nbsp;&nbsp;<a href='$print?periode=$periode' target='_blank' title='Print Kalender Akademik'><img src='template/images/printer.png'> <b>Print</b></a>
	</div>
	</div>";
?>
