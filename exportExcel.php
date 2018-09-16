<?php

    require_once('data/phpExcel/Classes/PHPExcel.php');

//====================================================================================
//  INITIALISATION
//====================================================================================

    $xlsx = new PHPExcel();

//====================================================================================
//  FILL EXCEL-FILE
//====================================================================================

    $xlsx->getActiveSheet()->setCellValue('A1','hello world');
    $xlsx->getActiveSheet()->setTitle('Test001');

//====================================================================================
//  EXPORT EXCEL-FILE
//====================================================================================

    $filename = 'helloWorld.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $xlsxWriter = PHPExcel_IOFactory::createWriter($xlsx,'Excel2007');
    $xlsxWriter->save('php://output');

?>