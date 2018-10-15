<?php

require 'data/phpspreadsheet/vendor/autoload.php';
require("header.php");

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    //for(int i=0;i<)
    if(isset($_POST['create_excel']))
    {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'OOEBV - Mannschaftsmeisterschaft')
        ->setCellValue('H1', '2017/2018')
        ->setCellValue('A2', '6.-10. Meisterschaftsrunde - Rueckrunde')
        ->setCellValue('A3', 'SPIELERRANGLISTE')
        ->setCellValue('E3', 'Stand per 09.02.2018')
        ->setCellValue('F3', 'Stand per 09.02.2018')
        ->setCellValue('A8', 'Vereinsname')
        ->setCellValue('F8', 'Vereins-Nr.')
        ->setCellValue('G8', 'Aenderungen/Mannschaftsf.')
        ->setCellValue('H8', 'Handy-Nr.')
        ->setCellValue('I8', 'E-Mail');


        $spreadsheet->getActiveSheet()->mergeCells('A1:D1');
        $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:D3');
        $spreadsheet->getActiveSheet()->mergeCells('A8:E8');
        $spreadsheet->getActiveSheet()->mergeCells('F3:G3');
        $spreadsheet->getActiveSheet()->setTitle('Spielerranglisten');


        $writer = new Xlsx($spreadsheet);
        $writer->save('vorlageExcel.xlsx');

    }

    echo'
        <h2 class="stagfade1">Excel erstellen</h1>
        <hr>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">

        <div>
          <button type="submit" name="create_excel" value="post-value">Excel erstellen
        </div>

        </form>
    ';

include("footer.php");

?>