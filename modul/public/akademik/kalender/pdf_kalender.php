<?php
include "../../../../config/session.php";
require('../../../../config/fpdf/fpdf.php');
include "../../../../config/koneksi.php";

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
	$this->SetX(60);
	$this->SetFont('Arial','B',11);
	$title3="KALENDER AKADEMIK $_GET[periode]";
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
    $w=array(10,120,30,30);
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
	$q_kalender=mssql_query("
							SELECT tt_kalender_akademik.kalender_akademik_awal, 
								tt_kalender_akademik.kalender_akademik_akhir, 
								tm_keterangan_kalender.keterangan_kalender_nama
							FROM tt_kalender_akademik 
								INNER JOIN tm_keterangan_kalender ON tt_kalender_akademik.keterangan_kalender_id = tm_keterangan_kalender.keterangan_kalender_id
							WHERE (tt_kalender_akademik.periode_id = '$_GET[periode]')
							ORDER BY tt_kalender_akademik.kalender_akademik_awal");
	$no=1;
	while($r_kalender=mssql_fetch_array($q_kalender))
	{
  		$nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                      "Juni", "Juli", "Agustus", "September", 
                      "Oktober", "November", "Desember");
					  
		$awal 		= date('Y-m-d',strtotime($r_kalender[kalender_akademik_awal]));
		$tgl_awal 	= substr($awal,8,2);
		$bln_awal	= $nama_bln[abs(substr($awal,5,2))];
		$thn_awal	= substr($awal,0,4);
		$tanggal_awal	= $tgl_awal." ".$bln_awal." ".$thn_awal;
		
		$akhir 		= date('Y-m-d',strtotime($r_kalender[kalender_akademik_akhir]));
		$tgl_akhir 	= substr($akhir,8,2);
		$bln_akhir	= $nama_bln[abs(substr($akhir,5,2))];
		$thn_akhir	= substr($akhir,0,4);
		$tanggal_akhir	= $tgl_akhir." ".$bln_akhir." ".$thn_akhir;
        
		$this->Cell($w[0],6,$no,1,0,'L',$fill);
        $this->Cell($w[1],6,$r_kalender[keterangan_kalender_nama],1,0,'L',$fill);
        $this->Cell($w[2],6,$tanggal_awal,1,0,'L',$fill);
        $this->Cell($w[3],6,$tanggal_akhir,1,0,'L',$fill);
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
$header=array('No.','Agenda Kegiatan','Tanggal Awal','Tanggal Akhir');
$pdf->SetFont('Arial','',9);
$pdf->AddPage(P,A4);
$pdf->FancyTable($header);
$pdf->Output("Kalender Akademik.pdf",D);
?>

