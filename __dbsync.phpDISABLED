<?php
    require("header.php");

/////////////////////////////////////////////////////////////////////////////////////
//  PHP-Section
/////////////////////////////////////////////////////////////////////////////////////

    if(isset($_POST['rsave']))
    {
        MySQLSave('recovery_'.date("Y-m-d-H-i"));
        MySQLSave('recovery');

        Redirect(ThisPage());
    }

    if(isset($_POST['rload']))
    {
        $stream = fopen("backup/recovery.sql", "r");
        if($stream)
        {
            while(($line = fgets($stream)) !== false)
            {
                MySQLNonQuery($line);
            }
            fclose($stream);
        }

        Redirect(ThisPage());
    }

    if(isset($_POST['save']))
    {

    }

    if(isset($_POST['load']))
    {

    }

/////////////////////////////////////////////////////////////////////////////////////
//  HTML-Section
/////////////////////////////////////////////////////////////////////////////////////

    echo '
        <h1 class="stagfade1">MySQL-Datenbank Sichern/Aktualisieren</h1>
        <br><br><br>

        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <center>
                <h3>(Standard)Anzuwenden wenn generelle &auml;nderungen an der Datenbank get&auml;tigt wurden.</h3>
                <button type="submit" name="rsave">Recovery-Datenbank Sichern</button>
                <button type="submit" name="rload">Recovery-Datenbank Laden</button>
            </center>
            <br><br>

            <center>
                <h3>Manuelles Sichern/Laden von Datenbanken</h3>
                <button type="submit" name="save" disabled>Datenbank Sichern</button>
                <button type="submit" name="load" disabled>Bestimmte Datenbank Laden</button>
            </center>
        </form>
    ';

    include("footer.php");
?>