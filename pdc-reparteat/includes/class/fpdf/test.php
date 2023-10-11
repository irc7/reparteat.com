<?php 
require('fpdf.php');
require('extends.class.php');
	
	$pdf = new PDF('L','mm','A4');
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	//$pdf->Image('img/19.jpg' , 0 ,0, 0 , 0,'JPG', 'http://www.euromediagrupo.es');
	$pdf->Ln();
	$pdf->Cell(50,10,utf8_decode('¡Hola Mundo!'),1,0,'L');
	$pdf->Cell(50,10,utf8_decode('¡Hola Mundo2!'),1,1,'L');
	$pdf->Ln();
	$pdf->Cell(40,20);
	$pdf->Write(5,'A continuación mostramos una imagen ');
	$pdf->Output();
	
	
	
	

?>

