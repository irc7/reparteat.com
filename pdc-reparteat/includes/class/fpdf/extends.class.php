<?php class PDF extends FPDF {//Cabecera de página	function Header(){		$this->Image('img/logo.jpg',10,10,50);		$this->SetFont('Arial','B',12);		$this->Cell(0,20,utf8_decode('Edición de PDF'),0,0,'R');	}//Pie de página	function Footer() {		$this->SetY(-10);		$this->SetFont('Arial','I',8);		$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');	}	}?>