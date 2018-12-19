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


    if(CheckPermission("AddDate"))
    {
        echo '<h1>Termine Importieren</h1><hr>';

        if(isset($_GET['selectColumns']))
        {
            echo '<h3>Spalten zuordnen</h3><br>';

            $csvFile = fopen(Setting::Get("TmpAgendaInsertFile"),'r');

            echo '<center><table>';

            $first = true;
            while($line = fgets($csvFile))
            {
                $cells = explode(';',$line);

                if($first)
                {
                    echo '<tr>';
                    foreach($cells as $cell)
                    {
                        echo '
                        <td>
                            <select name="" id="">
                                <option value="">--- W&auml;hlen ---</option>
                                <option value="">Titel</option>
                                <option value="">Beschreibung</option>
                                <option value="">Anfangsdatum</option>
                                <option value="">Enddatum</option>
                                <option value="">Ort</option>
                                <option value="">Zeit</option>
                                <option value="">Kategorie</option>
                                <option value="">Ignorieren</option>
                            </select>
                        </td>
                        ';
                    }
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
            echo '</table></center>';

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