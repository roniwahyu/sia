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
	$this->SetX(120);
	$this->SetFont('Arial','B',11);
	$title3="JADWAL UJIAN $_GET[periode]";
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
    $w=array(10,40,50,30,80,20,30,15);
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
	$q_jadwal=mssql_query("SELECT DISTINCT tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.jadwal_ujian_tanggal, 
								tt_jadwal_ujian.jam_id_awal, 
								tt_jadwal_ujian.matakuliah_id, 
								tt_jadwal_ujian.kelas_id, 
								tm_ujian_tipe.ujian_tipe_nama,
								tt_jadwal_ujian.ruang_id, 
								tm_matakuliah.matakuliah_nama,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_awal) AS jam_waktu_awal,
								(SELECT jam_waktu
								FROM tm_jam
								WHERE tm_jam.jam_id=tt_jadwal_ujian.jam_id_akhir) AS jam_waktu_akhir
							FROM tt_jadwal_ujian 
								INNER JOIN tm_matakuliah ON tt_jadwal_ujian.matakuliah_id = tm_matakuliah.matakuliah_id
								INNER JOIN tm_ujian_tipe ON tt_jadwal_ujian.ujian_tipe_id=tm_ujian_tipe.ujian_tipe_id
								INNER JOIN tt_jadwal ON tt_jadwal_ujian.matakuliah_id = tt_jadwal.matakuliah_id
									AND tt_jadwal_ujian.kelas_id = tt_jadwal.kelas_id
									AND tt_jadwal_ujian.periode_id = tt_jadwal.periode_id
									AND tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]'
							WHERE (tt_jadwal_ujian.periode_id = '$_GET[periode]')
								AND (tt_jadwal_ujian.ujian_tipe_id LIKE '$_GET[ujian_tipe]')
							ORDER BY tt_jadwal_ujian.jadwal_ujian_tanggal,
								tt_jadwal_ujian.jam_id_awal,
								tt_jadwal_ujian.jadwal_ujian_id,
								tt_jadwal_ujian.matakuliah_id,
								tt_jadwal_ujian.kelas_id"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$tgl = tgl_indo($r_jadwal[jadwal_ujian_tanggal]);
		$this->Cell($w[0],6,$no,1,0,'L',$fill);
		$this->Cell($w[1],6,$tgl,1,0,'L',$fill);
        $this->Cell($w[2],6,$r_jadwal[ujian_tipe_nama],1,0,'L',$fill);
        $this->Cell($w[3],6,$r_jadwal[matakuliah_id],1,0,'C',$fill);
        $this->Cell($w[4],6,$r_jadwal[matakuliah_nama],1,0,'L',$fill);
        $this->Cell($w[5],6,$r_jadwal[kelas_id],1,0,'C',$fill);
        $this->Cell($w[6],6,$r_jadwal[jam_waktu_awal]." - ".$r_jadwal[jam_waktu_akhir],1,0,'C',$fill);
        $this->Cell($w[7],6,$r_jadwal[ruang_id],1,0,'C',$fill);
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
$header=array('No.','Tanggal','Tipe Ujian','Kode Mata Kuliah','Nama Matakuliah','Kelas','Waktu','Ruang');
$pdf->SetFont('Arial','',9);
$pdf->AddPage(L,A4);
$pdf->FancyTable($header);
$pdf->Output("Jadwal Ujian.pdf",D);
?>

