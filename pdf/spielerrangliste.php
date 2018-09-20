<?php
    session_start();
    require('../data/functions.php');
    require('../data/fpdf/main_functions.php');



    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $pdf->Image('../content/ooebv.png',170,10,25);

    $pdf->Output();

?>