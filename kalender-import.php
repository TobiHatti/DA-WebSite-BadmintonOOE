<?php
    require("header.php");

    if(isset($_POST['uploadCSV']))
    {
        try
        {
            $fileUploader = new FileUploader();
            $fileUploader->SetFileElement("csvUpload");
            $fileUploader->SetFileTypes("csv");
            $fileUploader->SetPath("files/kalender/uploadInsert/");
            $fileUploader->SetName("agendaImport".date("Y-m-d"));
            $fileUploader->OverrideDuplicates(false);
            $fileUploader->SetSQLEntry("UPDATE settings SET value = '".FileUploaderOutput::FilenamePlusExtension."' WHERE setting = 'TmpAgendaInsertFile'");
            $fileUploader->Upload();
        }
        catch(Exception $ex)
        {
            Redirect(ThisPage("+error"));
            die();
        }

        Redirect(ThisPage("-error","+selectColumns"));
        die();
    }

    if(isset($_POST['checkAgendaImport']))
    {
        $i=0;
        $importTableheader = array();
        $skipFirstLine = isset($_POST['skipFirstLine']) ? 1 : 0;
        while(isset($_POST['columnAssignment'.$i])) array_push($importTableheader, $_POST['columnAssignment'.$i++]);

        $sqlStatement = 'INSERT INTO agenda (';
        $first = true;
        for($i = 0; $i < count($importTableheader); $i++)
        {
            if(!StartsWith($importTableheader[$i],'ignore'))
            {
                if($first) $sqlStatement .= $importTableheader[$i];
                else $sqlStatement .= ','.$importTableheader[$i];
                $first = false;
            }
        }
        $sqlStatement .= ') VALUES ';

        $csvFile = fopen(Setting::Get("TmpAgendaInsertFile"),'r');

        if($skipFirstLine) fgets($csvFile);

        $lineFirst = true;
        while($line = fgets($csvFile))
        {
            $rowData = explode(';',$line);

            $rowData = str_replace("'",'&apos;',$rowData);


            if($lineFirst) $sqlStatement .= ' (';
            else $sqlStatement .= ', (';

            $lineFirst = false;

            $first = true;
            for($i = 0; $i < count($rowData); $i++)
            {
                if(!StartsWith($importTableheader[$i],'ignore'))
                {
                    if($first) $sqlStatement .= "'".$rowData[$i]."'";
                    else $sqlStatement .= ",'".$rowData[$i]."'";
                    $first = false;
                }
            }

            $sqlStatement .= ') ';


        }

        MySQL::NonQuery($sqlStatement);
        
        Redirect("/kalender");
    }


    if(CheckPermission("AddDate"))
    {
        echo '<h1>Termine Importieren</h1><hr>';

        if(isset($_GET['selectColumns']))
        {
            echo '<h3>Spalten zuordnen</h3><br>';

            $csvFile = fopen(Setting::Get("TmpAgendaInsertFile"),'r');

            echo '
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <center>
                        '.Tickbox("skipFirstLine","skipFirstLine",'Erste Zeile ist Tabellenkopf <span title="Ankreuzen, wenn erste Zeile der Datei die Spaltennamen beinhaltet"><i class="far fa-question-circle"></i></span>',true).'
                        <br>
                        <table class="agendaImportTable">
            ';

            $first = true;
            while($line = fgets($csvFile))
            {
                $cells = explode(';',$line);

                if($first)
                {
                    echo '<tr>';
                    $i=0;
                    foreach($cells as $cell)
                    {
                        echo '
                        <td>
                            <select required name="columnAssignment'.$i.'" id="colAssign'.$i.'" onchange="CheckColumnDuplicatesCSVImport()" >
                                <option value="" disabled selected>--- W&auml;hlen ---</option>
                                <option value="title">Titel</option>
                                <option value="description">Beschreibung</option>
                                <option value="date_begin">Anfangsdatum</option>
                                <option value="date_end">Enddatum</option>
                                <option value="location">Ort</option>
                                <option value="time_start">Anfangszeit</option>
                                <option value="time_end">Endzeit</option>
                                <option value="category">Kategorie</option>
                                <option value="participant">Teilnehmer</option>
                                <option value="responsible">Verantwortlicher</option>
                                <option value="additional_info">Zusatzinfos</option>
                                <option value="ignore'.$i++.'">- Ignorieren -</option>
                            </select>
                        </td>
                        ';
                    }

                    echo '<input type="hidden" value="'.$i.'" id="colCounter"/>';

                    echo '</tr>';
                    $first = false;
                }

                echo '<tr>';
                foreach($cells AS $cell)
                {
                    echo '<td>'.$cell.'</td>';
                }
                echo '</tr>';
            }
            echo '
                        </table>

                        <br><br>
                        <button type="submit" disabled name="checkAgendaImport" id="checkAndContinueBtn">Pr&uuml;fen und fortfahren</button>
                    </center>
                </form>
            ';

            fclose($csvFile);

        }
        else
        {
            echo '<center>';
            if(isset($_GET['error'])) echo '<h3><b>Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.</b></h3><br>';

            echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                        <h3> CSV-Datei zum Import ausw&auml;hlen:</h3>
                        <br>
                        '.FileButton("csvUpload","csvUpload").'
                        <br><br>
                        <button type="submit" name="uploadCSV">Absenden</button>

                    </form>
                </center>

            ';
        }



    }

    require("footer.php");
?>