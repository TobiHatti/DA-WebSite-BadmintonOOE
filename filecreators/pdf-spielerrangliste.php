<?php
    session_start();
    require('../data/functions.php');
    require('../data/mysql.lib.php');
    require('../data/mysql.lib.new.php');
    require('../data/string.lib.php');
    require('../data/mysql_connect.php');
    require('../data/fpdf/main_functions.php');

    $year = $_GET['year'];
    $club = $_GET['club'];

    // Debug
    $showBorders = false;

    // Colors
    list($color1R, $color1G, $color1B) = sscanf('#'.SQL::Fetch("ranglisten_settings","value","setting","Y".$year."ColorA"), "#%02x%02x%02x");
    list($color2R, $color2G, $color2B) = sscanf('#'.SQL::Fetch("ranglisten_settings","value","setting","Y".$year."ColorB"), "#%02x%02x%02x");

    list($colorMarkR, $colorMarkG, $colorMarkB) = sscanf('#'.SQL::Fetch("ranglisten_settings","value","setting","HighlightColor"), "#%02x%02x%02x");

    // Initialisation
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();


    // Header
    $pdf->SetFillColor($color1R ,$color1G,$color1B);

    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(125,15,LetterCorrection("OÖBV - MANNSCHAFTSMEISTERSCHAFT"),$showBorders,0,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(65,15,"$year",$showBorders,1,'L',true);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,8,LetterCorrection("6. - 10. Meisterschaftsrunde - Rückrunde"),$showBorders,1,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(105,15,"S P I E L E R R A N G L I S T E",$showBorders,0,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(85,15,"Stand per 11.02.2015",$showBorders,1,'L',true);

    $pdf->SetFillColor($color2R ,$color2G,$color2B);
    $pdf->Cell(190,2,"",$showBorders,1,'L',true);

    $pdf->Image('../content/ooebv.png',168,13,25);

    $pdf->Ln();
    // End of Header




    if($club == "alle") $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year'";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $club)
        {
            if($first) $sqlClubExtension = "club = '$club'";
            else $sqlClubExtension .= " OR club = '$club'";

            $first = false;
        }

        $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND ($sqlClubExtension)";
    }
    else $strSQLc = "SELECT DISTINCT club FROM reihung WHERE year = '$year' AND club = '$club'";



    $first = true;
    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['club'];
        $clubVals = SQL::FetchRow("vereine","kennzahl",$club);


        if(!$first) $pdf->AddPage();
        $first = false;


        // Section Header
        $pdf->SetFillColor($color1R ,$color1G,$color1B);
        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(98,10,LetterCorrection($clubVals['verein'].' '.$clubVals['ort']),$showBorders,0,'L',true);
        $pdf->Cell(20,10,$club,$showBorders,0,'C',true);

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(32,5,LetterCorrection("Änderungen/"),$showBorders,0,'C',true);


        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(39,10,"Handy-Nr.",$showBorders,0,'C',true);
        $pdf->Cell(1,5,"",$showBorders,1,'C',true);

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(118,5,"",$showBorders,0,'C',false);
        $pdf->Cell(32,5,"Mannschaftsf.",$showBorders,0,'C',true);
        $pdf->Cell(39,5,"",$showBorders,0,'C',false);
        $pdf->Cell(1,5,"",$showBorders,1,'C',true);

        $pdf->SetFillColor($color2R ,$color2G,$color2B);
        $pdf->Cell(190,2,"",$showBorders,1,'L',true);

        $pdf->Ln();
        //End of Section Header

        // Table Header
        $pdf->SetFont('Arial','BU',9);
        $pdf->Cell(8,5,"",$showBorders,0,'L');
        $pdf->Cell(30,5,"Nachname",$showBorders,0,'L');
        $pdf->Cell(30,5,"Vorname",$showBorders,0,'L');
        $pdf->Cell(18,5,"Mitgl. Nr.",$showBorders,0,'C');
        $pdf->Cell(12,5,"Team",$showBorders,0,'C');
        $pdf->Cell(20,5,"Vereins-Nr.",$showBorders,0,'C');
        $pdf->Cell(32,5,"",$showBorders,0,'C');
        $pdf->Cell(40,5,"",$showBorders,1,'C');
        // End of Table Header


        $i=1;

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(190,5,"Herren",$showBorders,1,'L');

        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='M' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            $pdf->SetFont('Arial','',9);

            if($focus)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($color2R ,$color2G,$color2B);
            }

            $pdf->Cell(8,5,$i++.'.',$showBorders,0,'R',$focus);
            $pdf->Cell(30,5,LetterCorrection($row['lastname']),$showBorders,0,'L',$focus);
            $pdf->Cell(30,5,LetterCorrection($row['firstname']),$showBorders,0,'L',$focus);
            $pdf->Cell(18,5,$row['number'],$showBorders,0,'C',$focus);
            $pdf->Cell(12,5,$row['team'],$showBorders,0,'C',$focus);
            $pdf->Cell(20,5,$row['club'],$showBorders,0,'C',$focus);

            if($highlight)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($colorMarkR ,$colorMarkG,$colorMarkB);
            }

            $pdf->Cell(32,5,LetterCorrection($row['mf']),$showBorders,0,'C',$focus OR $highlight);

            if($highlight)  $pdf->SetFont('Arial','',9);

            $pdf->Cell(40,5,$row['mobile_nr'],$showBorders,1,'C',$focus);
        }


        $i=1;
        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(190,5,"Damen",$showBorders,1,'L');

        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='W' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            $pdf->SetFont('Arial','',9);

            if($focus)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($color2R ,$color2G,$color2B);
            }

            $pdf->Cell(8,5,$i++.'.',$showBorders,0,'R',$focus);
            $pdf->Cell(30,5,LetterCorrection($row['lastname']),$showBorders,0,'L',$focus);
            $pdf->Cell(30,5,LetterCorrection($row['firstname']),$showBorders,0,'L',$focus);
            $pdf->Cell(18,5,$row['number'],$showBorders,0,'C',$focus);
            $pdf->Cell(12,5,$row['team'],$showBorders,0,'C',$focus);
            $pdf->Cell(20,5,$row['club'],$showBorders,0,'C',$focus);

            if($highlight)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($colorMarkR ,$colorMarkG,$colorMarkB);
            }

            $pdf->Cell(32,5,LetterCorrection($row['mf']),$showBorders,0,'C',$focus OR $highlight);

            if($highlight)  $pdf->SetFont('Arial','',9);

            $pdf->Cell(40,5,$row['mobile_nr'],$showBorders,1,'C',$focus);
        }
    }


    $pdf->Output();

?>