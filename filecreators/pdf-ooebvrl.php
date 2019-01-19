<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    session_start();
    require("../headerincludes.php");
    require('../data/fpdf/main_functions.php');


    // Debug
    $showBorders = true;

    list($colorR, $colorG, $colorB) = sscanf('#'.MySQL::Scalar("SELECT headerColor FROM ooebvrl_tables WHERE tableFilename = ?",'s',$_GET['table']), "#%02x%02x%02x");


    // Initialisation
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();


    // Header
    $pdf->SetFillColor($colorR ,$colorG,$colorB);

    $pdf->SetTextColor(255,255,255);

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(50,15,"***",$showBorders,0,'C',true);
    $pdf->Cell(90,15,"Bald verfuegbar",$showBorders,0,'C',true);
    $pdf->Cell(50,15,"***",$showBorders,0,'C',true);

    $pdf->Ln();

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(12,5,"Pos.",$showBorders,0,'C',true);
    $pdf->Cell(16,5,"MgNr.",$showBorders,0,'C',true);
    $pdf->Cell(40,5,"Name",$showBorders,0,'L',true);
    $pdf->Cell(42,5,"Verein",$showBorders,0,'L',true);
    $pdf->Cell(10,5,"Jg.",$showBorders,0,'C',true);
    $pdf->Cell(14,5,"1.Rd",$showBorders,0,'C',true);
    $pdf->Cell(14,5,"2.Rd",$showBorders,0,'C',true);
    $pdf->Cell(14,5,"3.Rd",$showBorders,0,'C',true);
    $pdf->Cell(14,5,"Str.",$showBorders,0,'C',true);
    $pdf->Cell(14,5,"Ges.",$showBorders,0,'C',true);



    $pdf->Output();

?>