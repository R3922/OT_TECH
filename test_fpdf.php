<?php
require 'vendor/setasign/fpdf/fpdf.php'; // Incluir directamente el archivo FPDF

// Crear el PDF usando FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, '¡FPDF instalado correctamente!');
$pdf->Output();
?>
