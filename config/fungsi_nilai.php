<?php
function nilai_tipe($nilai)
{
	include "koneksi.php";
	$q=mssql_query("SELECT * FROM tm_nilai_tipe ORDER BY nilai_tipe_id");
	while($r=mssql_fetch_array($q))
	{
			if($nilai>=$r[nilai_tipe_min] && $nilai<=$r[nilai_tipe_max])
			{
				$nilai_tipe=$r[nilai_tipe_id];
			}
	}
	return $nilai_tipe;
}
?>