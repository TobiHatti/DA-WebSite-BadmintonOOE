<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "badminton-ooe";

    $error_Message = "
    <style>*{font-family:sans-serif;line-height: 25px;}</style>
    <br><br>
    <b>BITTE IMPORTIERE DIE ANGEFUEGTE .SQL-DATEI IN PHP-MY-ADMIN</b><br><br>
    <b>PFAD ZUR SQL-DATEI:</b>&nbsp;&nbsp;&nbsp;<u>root( = Badminton) \\ backup \\ recovery.sql</u><br><br>
    <b>IMPORTIEREN DER SQL-DATEI IN DIE DATENBANK:</b><br>
    1) http://localhost/phpmyadmin aufrufen<br>
    2) Wenn in der linken Spalte keine Datenbank mit dem Namen \"badminton.ooe\" aufscheint, auf \"Neu\" klicken.
    3) Im Feld <i>\"Datenbankname\"</i> > <b>badminton-ooe</b> eintragen.<br>
    4) Im Feld <i>\"Kollation\"</i> > <b>utf8_unicode_ci</b> auswaehlen.<br>
    5) <b>\"Anlegen\"</b> anklicken.<br>
    6) In der Oberen Menueleiste den Schalter <b>\"Importieren\"</b> auswaehlen.<br>
    7) Auf \"Datei auswaehlen\" klicken und die Datei \"recovery.sql\" auswaehlen. Anschliessend unten auf \"OK\" klicken.<br>
    8) Wenn fehler auftreten, bei Tobi melden. Ansonsten kann phpMyAdmin nun geschlossen werden.<br>

    ";


    $link = mysqli_connect($servername, $username, $password, $database) or die($error_Message);
?>
