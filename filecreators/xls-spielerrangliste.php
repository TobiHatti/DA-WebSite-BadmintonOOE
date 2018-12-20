<?php

    require("../downloading.php");
    require('../data/phpspreadsheet/vendor/autoload.php');

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $locale = 'de';
    $validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
    if (!$validLocale) {
        echo 'Unable to set locale to ' . $locale . " - reverting to en_us" . PHP_EOL;
    }

    $year = $_GET['year'];
    $club = $_GET['club'];
    $originalClub = $club;


    $accentColor1Sett = "Y".$year."ColorA";
    $accentColor2Sett = "Y".$year."ColorB";
    $highlightColorSett = "HighlightColor";
    $headerSubtitleSett = "Y".$year."HeaderSubtitle";

    $color1 = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor1Sett);
    $color2 = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$accentColor2Sett);
    $colorHilight = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$highlightColorSett);
    

    $spreadsheet = new Spreadsheet();
    $spreadsheet->setActiveSheetIndex(0);

//========================================================================================
//  TABLE SETUP
//========================================================================================

    // SETTING SHEET-NAME
    $spreadsheet->getActiveSheet()->setTitle('Spielerrangliste');

    // DEFINING COLUMN-WIDTHS
    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(3);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(17.29);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(14.57);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8.43);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(5.43);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10.29);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(16.57);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(36);

    $spreadsheet->getActiveSheet()->getStyle('D:H')->getAlignment()->setHorizontal('center');

//========================================================================================
//  TABLE HEADER
//========================================================================================

    // FILLING CELLS (Array-Method)
    $cellCluster = [
        ['OÖBV - Mannschaftsmeisterschaft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, $_GET['year']],
        [MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$headerSubtitleSett)],
        ['S P I E L E R R A N G L I S T E', NULL, NULL, NULL, 'Stand per 09.02.2018'],
    ];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A1');

    // MERGING CELLS
    $spreadsheet->getActiveSheet()->mergeCells('A1:H1');
    $spreadsheet->getActiveSheet()->mergeCells('A2:I2');
    $spreadsheet->getActiveSheet()->mergeCells('A3:D3');
    $spreadsheet->getActiveSheet()->mergeCells('E3:I3');

    // SETTING FONT SIZES
    $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle('I1')->getFont()->setSize(14);
    $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle('A3')->getFont()->setSize(14);
    $spreadsheet->getActiveSheet()->getStyle('E3')->getFont()->setSize(14);

    // COLORING CELLS
    $spreadsheet->getActiveSheet()->getStyle('A1:I5')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color1);

    $spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color2);

    // SETTING FONT STYLE
    $spreadsheet->getActiveSheet()->getStyle("A1:I6")->getFont()->setBold(true);

    // DEFINING ROW-HEIGHTS
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40.50);
    $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(21);
    $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(24);

    // SETTING TEXT ALIGNS
    $spreadsheet->getActiveSheet()->getStyle('A1:I6')->getAlignment()->setVertical('center');

    // ADDING IMAGE
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo');
    $drawing->setPath('../content/ooebv.png');
    $drawing->setHeight(145);
    $drawing->setCoordinates('I1');
    $drawing->setOffsetX(130);
    $drawing->setOffsetY(5);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());


/*
//========================================================================================
//  SECTION HEADER
//========================================================================================

    $startRow = 8;

    // FILLING CELLS (Array-Method)
    $cellCluster = [
        ['ATV Andorf',NULL,NULL,NULL,NULL,'301',"Änderungen/\nMannschaftsf.",'Handy-Nr.','E-Mail']
    ];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);

    // ALLOW LINE-BREAK
    $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getAlignment()->setWrapText(true);

    // MERGING CELLS
    $spreadsheet->getActiveSheet()->mergeCells('A'.$startRow.':E'.$startRow);

    // SETTING FONT SIZES
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':F'.$startRow)->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFont()->setSize(11);
    $spreadsheet->getActiveSheet()->getStyle('H'.$startRow.':I'.$startRow)->getFont()->setSize(12);

    // COLORING CELLS
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color1);

    $spreadsheet->getActiveSheet()->getStyle('A'.($startRow+1).':I'.($startRow+1))->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color2);

    // SETTING FONT STYLE
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);

    // SETTING TEXT ALIGNS
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getAlignment()->setVertical('center');
    $spreadsheet->getActiveSheet()->getStyle('F'.$startRow.':H'.$startRow)->getAlignment()->setHorizontal('center');

    // DEFINING ROW-HEIGHTS
    $spreadsheet->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
    $spreadsheet->getActiveSheet()->getRowDimension($startRow+1)->setRowHeight(9);

//========================================================================================
//  SEGMENT HEADER
//========================================================================================

    $startRow = 10;

    // FILLING CELLS (Array-Method)
    $cellCluster = [
        [NULL,"Nachname","Vorname","Mitgl. Nr.","Team","Vereins-Nr."],
    ];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);

    // SETTING FONT STYLE
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);

    // SETTING TEXT ALIGNS
    $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');

//========================================================================================
//  DATA ROW
//========================================================================================

    $startRow=12;

    // FILLING CELLS (Array-Method)
    $cellCluster = [
        [NULL,"Hajek","Christoph","32198","1","301","","",""]
    ];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);

    // INSERT NUMBER AS STRING TO KEEP THE "."
    $spreadsheet->getActiveSheet()->setCellValueExplicit(
        'A'.$startRow,
        "1.",
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
    );

    // SETTING TEXT ALIGNS
    $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');


//========================================================================================
//========================================================================================
//========================================================================================
//========================================================================================
//========================================================================================


//========================================================================================
//  SECTION HEADER
//========================================================================================

    $startRow = 8;

    $cellCluster = ['ATV Andorf',NULL,NULL,NULL,NULL,'301',"Änderungen/\nMannschaftsf.",'Handy-Nr.','E-Mail'];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
    $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->mergeCells('A'.$startRow.':E'.$startRow);
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':F'.$startRow)->getFont()->setSize(16);
    $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFont()->setSize(11);
    $spreadsheet->getActiveSheet()->getStyle('H'.$startRow.':I'.$startRow)->getFont()->setSize(12);
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color1);
    $spreadsheet->getActiveSheet()->getStyle('A'.($startRow+1).':I'.($startRow+1))->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF'.$color2);
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getAlignment()->setVertical('center');
    $spreadsheet->getActiveSheet()->getStyle('F'.$startRow.':H'.$startRow)->getAlignment()->setHorizontal('center');
    $spreadsheet->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
    $spreadsheet->getActiveSheet()->getRowDimension($startRow+1)->setRowHeight(9);

//========================================================================================
//  SEGMENT HEADER
//========================================================================================

    $startRow = 10;

    $cellCluster = [NULL,"Nachname","Vorname","Mitgl. Nr.","Team","Vereins-Nr."];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
    $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');

//========================================================================================
//  DATA ROW
//========================================================================================

    $startRow=12;

    $cellCluster = [NULL,"Hajek","Christoph","32198","1","301","","",""];
    $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
    $spreadsheet->getActiveSheet()->setCellValueExplicit('A'.$startRow,"1.",\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');

//========================================================================================
//========================================================================================
//========================================================================================
//========================================================================================
//========================================================================================
*/



    $pageCounter = 8;

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





    $rsc=mysqli_query($link,$strSQLc);
    while($rowc=mysqli_fetch_assoc($rsc))
    {
        $club = $rowc['club'];
        $clubVals = MySQL::Row("SELECT * FROM vereine WHERE kennzahl = ?",'s',$club);


        //========================================================================================
        //  SECTION HEADER
        //========================================================================================
        $startRow = $pageCounter;
        $pageCounter+=2;

        $cellCluster = [$clubVals['verein'].' '.$clubVals['ort'],NULL,NULL,NULL,NULL,$club,"Änderungen/\nMannschaftsf.",'Handy-Nr.','E-Mail'];
        $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
        $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->mergeCells('A'.$startRow.':E'.$startRow);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':F'.$startRow)->getFont()->setSize(16);
        $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFont()->setSize(11);
        $spreadsheet->getActiveSheet()->getStyle('H'.$startRow.':I'.$startRow)->getFont()->setSize(12);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF'.$color1);
        $spreadsheet->getActiveSheet()->getStyle('A'.($startRow+1).':I'.($startRow+1))->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF'.$color2);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getStyle('F'.$startRow.':H'.$startRow)->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
        $spreadsheet->getActiveSheet()->getRowDimension($startRow+1)->setRowHeight(9);
        //========================================================================================
        //  END OF SECTION HEADER
        //  SEGMENT HEADER
        //========================================================================================
        $startRow = $pageCounter;
        $pageCounter+=1;

        $cellCluster = [NULL,"Nachname","Vorname","Mitgl. Nr.","Team","Vereins-Nr."];
        $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.($startRow+1))->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');
        //========================================================================================
        //  END OF SEGMENT HEADER
        //========================================================================================

        $i=1;

        $startRow = $pageCounter;
        $pageCounter+=1;

        $cellCluster = ["Herren"];
        $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFont()->setBold(true);

        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='M' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            $startRow = $pageCounter;
            $pageCounter+=1;

            $cellCluster = [NULL,$row['lastname'],$row['firstname'],$row['number'],$row['team'],$row['club'],$row['mf'],$row['mobile_nr'],$row['email']];
            $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
            $spreadsheet->getActiveSheet()->setCellValueExplicit('A'.$startRow,$i++.".",\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');

            if($focus)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF'.$color2);
                $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFont()->setBold(true);
            }
            if($highlight)
            {
                $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF'.$colorHilight);
                $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFont()->setBold(true);
            }
        }


        $i=1;

        $pageCounter+=1;
        $startRow = $pageCounter;
        $pageCounter+=1;

        $cellCluster = ["Damen"];
        $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
        $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFont()->setBold(true);

        $strSQL = "SELECT * FROM reihung INNER JOIN members ON reihung.member = members.number WHERE reihung.type='W' AND reihung.club = '$club' AND reihung.year = '$year' ORDER BY reihung.position ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $highlight = ($row['mf']!='' AND !(SubStringFind($row['mf'],'MF')));
            $focus = (SubStringFind($row['mf'],'MF'));

            $startRow = $pageCounter;
            $pageCounter+=1;

            $cellCluster = [NULL,$row['lastname'],$row['firstname'],$row['number'],$row['team'],$row['club'],$row['mf'],$row['mobile_nr'],$row['email']];
            $spreadsheet->getActiveSheet()->fromArray($cellCluster,NULL,'A'.$startRow);
            $spreadsheet->getActiveSheet()->setCellValueExplicit('A'.$startRow,$i++.".",\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $spreadsheet->getActiveSheet()->getStyle('D'.$startRow.':F'.$startRow)->getAlignment()->setHorizontal('center');

            if($focus)
            {
                $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF'.$color2);
                $spreadsheet->getActiveSheet()->getStyle('A'.$startRow.':I'.$startRow)->getFont()->setBold(true);
            }
            if($highlight)
            {
                $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF'.$colorHilight);
                $spreadsheet->getActiveSheet()->getStyle('G'.$startRow)->getFont()->setBold(true);
            }
        }

        $pageCounter+=1;
    }


    $filename = 'Spielerrangliste-'.$year.'-'.$originalClub.'.xlsx';
    $path = "../files/spielerranglisten/$filename";
    $pathDL = "files/spielerranglisten/$filename";

    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    Redirect("/forceDownload?file=".urlencode($pathDL));
?>