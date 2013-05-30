<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            var dok = $("#dokumen").val();
            $.ajax({
                url: "modul/public/akademik/dokumen/dokumen_data.php",
                data: "periode="+periode+"&dok="+dok,
                cache: false,
                success: function(msg){
                    $("#folder").html(msg);
                }
            });
          });
        });
   </script>

<script type="text/javascript">
function dokumen()
{
	var htmlobjek;
    var dok = $("#dokumen").val();
    var periode = $("#periode").val();
	$.ajax(
	{
        url: "modul/public/akademik/dokumen/dokumen_data.php",
        data: "periode="+periode+"&dok="+dok,
		cache: false,
		success: function(msg)
		{
			$("#folder").html(msg);
		}
	});
}
</script>

<?php
	echo"
	<div class='content'>
	<h3>Akademik &#187; Dokumen & File</h3>
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
	echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b>Cari Dokumen</b><input type='text' onkeyup='javascript:dokumen(this)' id='dokumen'>
	<br class='clear'>
	<br class='clear'>
	<div id='folder'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>";
	$q_folder=mssql_query("SELECT * FROM tm_folder WHERE folder_aktif='Y'");
	while($r_folder=mssql_fetch_array($q_folder))
	{	
		echo "<tr><th colspan='4'><a title='$r_folder[folder_keterangan]'>$r_folder[folder_nama]</a></th></tr>";
		$no=1;
		$q_file=mssql_query("SELECT * FROM tt_file WHERE folder_id='$r_folder[folder_id]' AND periode_id='$periode'");
		while($r_file=mssql_fetch_array($q_file))
		{
			$tgl = tgl_indo($r_file[file_tanggal]);
			echo"<tr class='on'>
				<td width='40px'>$no</td>
				<td><a href='files/$r_file[file_tmp]' title='$r_file[file_keterangan]' target='_blank'>$r_file[file_nama]</a></td>
				<td>$r_file[file_keterangan]</td>
				<td>$tgl</td>
			</tr>";
			$no++;
		}
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>
	</div>
	</div>";
?>
