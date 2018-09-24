<?php
    session_start();
    require('../data/functions.php');
    require('../data/fpdf/main_functions.php');

    // Debug
    $showBorders = false;

    // Colors
    $color1R = 50;
    $color1G = 200;
    $color1B = 50;

    $color2R = 0;
    $color2G = 100;
    $color2B = 155;

    // Initialisation
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();


    // Header
    $pdf->SetFillColor($color1R ,$color1G,$color1B);

    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(125,15,"OÖBV - MANNSCHAFTSMEISTERSCHAFT",$showBorders,0,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(65,15,"2015/2016",$showBorders,1,'L',true);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,8,"6. - 10. Meisterschaftsrunde - Rückrunde",$showBorders,1,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(105,15,"S P I E L E R R A N G L I S T E",$showBorders,0,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(85,15,"Stand per 11.02.2015",$showBorders,1,'L',true);

    $pdf->SetFillColor($color2R ,$color2G,$color2B);
    $pdf->Cell(190,2,"",$showBorders,1,'L',true);

    $pdf->Image('../content/ooebv.png',168,13,25);

    $pdf->Ln();
    // End of Header

    // Section Header
    $pdf->SetFillColor($color1R ,$color1G,$color1B);

    $pdf->Cell(98,10,"ATV Andorf",$showBorders,0,'L',true);
    $pdf->Cell(20,10,"301",$showBorders,0,'C',true);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(32,5,"Änderungen/",$showBorders,0,'C',true);


    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(39,10,"Handy-Nr.",$showBorders,0,'C',true);
    $pdf->Cell(1,5,"",$showBorders,1,'C',true);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(118,5,"",$showBorders,0,'C',false);
    $pdf->Cell(32,5,"Mannschaftsf.",$showBorders,0,'C',true);
    $pdf->Cell(39,5,"",$showBorders,0,'C',false);
    $pdf->Cell(1,5,"",$showBorders,1,'C',true);

    $pdf->SetFillColor($color2R ,$color2G,$color2B);
    $pdf->Cell(190,2,"",$showBorders,1,'L',true);

    $pdf->Ln();
    // End of Section Header

    // Table Header
    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(8,5,"",$showBorders,0,'L');
    $pdf->Cell(30,5,"Nachname",$showBorders,0,'L');
    $pdf->Cell(30,5,"Vorname",$showBorders,0,'L');
    $pdf->Cell(18,5,"Mitgl. Nr.",$showBorders,0,'C');
    $pdf->Cell(12,5,"Team",$showBorders,0,'C');
    $pdf->Cell(20,5,"Vereins-Nr.",$showBorders,0,'C');
    $pdf->Cell(32,5,"",$showBorders,0,'C');
    $pdf->Cell(40,5,"",$showBorders,1,'C');
    // End of Table Header

    $pdf->Cell(190,5,"Herren",$showBorders,1,'L');



    $pdf->SetFont('Arial','',9);
    for($i=1;$i<20;$i++)
    {
        $pdf->Cell(8,5,$i.'.',$showBorders,0,'R');
        $pdf->Cell(30,5,"Gumpoltsberger",$showBorders,0,'L');
        $pdf->Cell(30,5,"Jean-Christoph",$showBorders,0,'L');
        $pdf->Cell(18,5,"80402",$showBorders,0,'C');
        $pdf->Cell(12,5,"2",$showBorders,0,'C');
        $pdf->Cell(20,5,"301",$showBorders,0,'C');
        $pdf->Cell(32,5,"14.10.2016",$showBorders,0,'C');
        $pdf->Cell(40,5,"0664/8411881",$showBorders,1,'C');
    }



    $pdf->Output();

?>