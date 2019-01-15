<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    session_start();
    require("../headerincludes.php");
    require('../data/fpdf/main_functions.php');

    $year = $_GET['year'];
    $club = $_GET['club'];

    // Debug
    $showBorders = false;

    // Colors
    $accentColor1Sett = "Y".$year."ColorA";
    $accentColor2Sett = "Y".$year."ColorB";
    $highlightColorSett = "HighlightColor";
    $headerSubtitleSett = "Y".$year."HeaderSubtitle";
    $lastUpdateSett = "Y".$year."LastUpdate";

    list($color1R, $color1G, $color1B) = sscanf('#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor1Sett), "#%02x%02x%02x");
    list($color2R, $color2G, $color2B) = sscanf('#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor2Sett), "#%02x%02x%02x");

    list($colorMarkR, $colorMarkG, $colorMarkB) = sscanf('#'.MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$highlightColorSett), "#%02x%02x%02x");

    $lastChangeDate = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$lastUpdateSett);
    $lastChange = str_replace('ä','&auml;',strftime("%d.%m.%Y",strtotime($lastChangeDate)));

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
    $pdf->Cell(190,8,LetterCorrection(MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$headerSubtitleSett)),$showBorders,1,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(105,15,"S P I E L E R R A N G L I S T E",$showBorders,0,'L',true);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(85,15,"Stand per ".$lastChange,$showBorders,1,'L',true);

    $pdf->SetFillColor($color2R ,$color2G,$color2B);
    $pdf->Cell(190,2,"",$showBorders,1,'L',true);

    $pdf->Image('../content/ooebv.png',168,13,25);

    $pdf->Ln();
    // End of Header




    if($club == "alle") $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' GROUP BY members.clubID";
    else if(StartsWith($club,"M"))
    {
        $selectedClubs = str_replace('M','',$club);
        $clubArray = explode('-',$selectedClubs);

        $first = true;
        foreach($clubArray AS $sClub)
        {
            if($first) $sqlClubExtension = "members.clubID = '$sClub'";
            else $sqlClubExtension .= " OR members.clubID = '$sClub'";

            $first = false;
        }

        $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND ($sqlClubExtension) GROUP BY members.clubID";
    }
    else $strSQLc = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id INNER JOIN vereine ON members.clubID = vereine.kennzahl WHERE members_spielerranglisten.year = '$year' AND members.clubID = '$club' GROUP BY members.clubID";



    $first = true;
    $rsc=mysqli_query($link,$strSQLc);
    while($clubVals=mysqli_fetch_assoc($rsc))
    {
        $club = $clubVals['clubID'];


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

        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'M' AND members.clubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
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
            $pdf->Cell(18,5,$row['playerID'],$showBorders,0,'C',$focus);
            $pdf->Cell(12,5,$row['team'],$showBorders,0,'C',$focus);
            $pdf->Cell(20,5,$row['clubID'],$showBorders,0,'C',$focus);

            if($highlight)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($colorMarkR ,$colorMarkG,$colorMarkB);
            }

            $pdf->Cell(32,5,LetterCorrection($row['mf']),$showBorders,0,'C',$focus OR $highlight);

            if($highlight)  $pdf->SetFont('Arial','',9);

            $pdf->Cell(40,5,($row['mf']!="" ? $row['mobileNr'] : ''),$showBorders,1,'C',$focus);
        }


        $i=1;
        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(190,5,"Damen",$showBorders,1,'L');

        $strSQL = "SELECT * FROM members_spielerranglisten INNER JOIN members ON members_spielerranglisten.memberID = members.id WHERE members.gender = 'F' AND members.clubID = '$club' AND members_spielerranglisten.year = '$year' ORDER BY members_spielerranglisten.position ASC";
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
            $pdf->Cell(18,5,$row['playerID'],$showBorders,0,'C',$focus);
            $pdf->Cell(12,5,$row['team'],$showBorders,0,'C',$focus);
            $pdf->Cell(20,5,$row['clubID'],$showBorders,0,'C',$focus);

            if($highlight)
            {
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor($colorMarkR ,$colorMarkG,$colorMarkB);
            }

            $pdf->Cell(32,5,LetterCorrection($row['mf']),$showBorders,0,'C',$focus OR $highlight);

            if($highlight)  $pdf->SetFont('Arial','',9);

            $pdf->Cell(40,5,($row['mf']!="" ? $row['mobileNr'] : ''),$showBorders,1,'C',$focus);
        }
    }


    $pdf->Output();

?>