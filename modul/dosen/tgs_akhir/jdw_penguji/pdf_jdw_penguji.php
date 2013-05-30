<?php
include "../../../../config/session.php";
require('../../../../config/fpdf/fpdf.php');
include "../../../../config/koneksi.php";
include "../../../../config/fungsi_indotgl.php";

class PDF extends FPDF
{
	//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','',9);
    //Page number
    $this->Cell(0,10,''.$this->PageNo().'',0,0,'C');
}
//Colored table
function FancyTable($header)
{
	$this->Ln(6);
	$this->SetX(95);
	$this->SetFont('Arial','B',11);
	$title3="JADWAL PENGUJI $_GET[periode]";
	$this->Write(7,$title3);
	$this->Ln(20);
	
	$this->SetFont('Arial','',9);
    //Colors, line width and bold font
    $this->SetFillColor(255);
    $this->SetTextColor(0);
    $this->SetDrawColor(0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Header
    $w=array(10,20,40,40,30,25,15,15,15,15,15);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    //Color and font restoration
    $this->SetFillColor(255);
    $this->SetTextColor(0);
    $this->SetDrawColor(0);
    $this->SetLineWidth(.3);
    $this->SetFont('','');
    //Data
    $fill=false;
	$q_jadwal=mssql_query("SELECT tt_jadwal_sidang.mahasiswa_nim, 
									tm_mahasiswa.mahasiswa_nama, 
									tt_jadwal_sidang.penguji_kode_1, 
									tt_jadwal_sidang.penguji_kode_2, 
									tt_jadwal_sidang.ujian_tipe_id, 
									tt_jadwal_sidang.jadwal_sidang_tanggal, 
									tt_jadwal_sidang.jam_id_awal, 
									tt_jadwal_sidang.jam_id_akhir, 
									tt_jadwal_sidang.ruang_id,
									tt_jadwal_sidang.jadwal_sidang_ket, 
									tt_tugas_akhir.pegawai_kode_1 AS pembimbing_kode_1, 
									tt_tugas_akhir.pegawai_kode_2 AS pembimbing_kode_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_1)) AS pembimbing_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_tugas_akhir.pegawai_kode_2)) AS pembimbing_nama_2,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_jadwal_sidang.penguji_kode_1)) AS penguji_nama_1,
									  (SELECT     pegawai_nama
										FROM          tm_pegawai
										WHERE      (pegawai_kode = tt_jadwal_sidang.penguji_kode_2)) AS penguji_nama_2,
									  (SELECT     jam_waktu
										FROM          tm_jam
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_awal)) AS jam_waktu_awal,
									  (SELECT     jam_waktu
										FROM          tm_jam AS tm_jam_1
										WHERE      (jam_id = tt_jadwal_sidang.jam_id_akhir)) AS jam_waktu_akhir,
									  (SELECT     ujian_tipe_nama
										FROM          tm_ujian_tipe
										WHERE      (ujian_tipe_id = tt_jadwal_sidang.ujian_tipe_id)) AS ujian_tipe_nama
								FROM tt_jadwal_sidang 
									INNER JOIN tt_tugas_akhir ON tt_jadwal_sidang.mahasiswa_nim = tt_tugas_akhir.mahasiswa_nim 
									INNER JOIN tm_mahasiswa ON tt_jadwal_sidang.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
								WHERE     ((tt_jadwal_sidang.penguji_kode_1 = '$_SESSION[pegawai_kode]') OR (tt_jadwal_sidang.penguji_kode_2 = '$_SESSION[pegawai_kode]'))
									AND tt_jadwal_sidang.periode_id='$_GET[periode]'
								ORDER BY tt_jadwal_sidang.jadwal_sidang_tanggal"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal['jadwal_sidang_tanggal']);
		$this->Cell($w[0],6,$no,1,0,'L',$fill);
        $this->Cell($w[1],6,$r_jadwal[mahasiswa_nim],1,0,'C',$fill);
        $this->Cell($w[2],6,$r_jadwal[mahasiswa_nama],1,0,'L',$fill);
        $this->Cell($w[3],6,$r_jadwal[ujian_tipe_nama],1,0,'L',$fill);
        $this->Cell($w[4],6,$tgl,1,0,'L',$fill);
        $this->Cell($w[5],6,$r_jadwal[jam_waktu_awal]." - ".$r_jadwal[jam_waktu_akhir],1,0,'C',$fill);
        $this->Cell($w[6],6,$r_jadwal[ruang_id],1,0,'C',$fill);
        $this->Cell($w[7],6,$r_jadwal[pembimbing_kode_1],1,0,'C',$fill);
        $this->Cell($w[8],6,$r_jadwal[pembimbing_kode_2],1,0,'C',$fill);
        $this->Cell($w[9],6,$r_jadwal[penguji_kode_1],1,0,'C',$fill);
        $this->Cell($w[10],6,$r_jadwal[penguji_kode_2],1,0,'C',$fill);
		$this->Ln();
        $fill=!$fill;
		$no++;
    }
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();
$pdf->AliasNbPages();
//Column titles
$header=array('No.','NIM','Nama','Tipe Ujian','Tanggal','Waktu','Ruang','Pemb. 1','Pemb. 2','Peng. 1','Peng. 2');
$pdf->SetFont('Arial','',9);
$pdf->AddPage(L,A4);
$pdf->FancyTable($header);
$pdf->Output("Jadwal Penguji.pdf",D);
?>

