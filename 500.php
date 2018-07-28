<?php
    require("header.php");

    echo '
        <center>
            <span style="color: #CC0000; font-size: 50pt; font-weight: lighter;">ERROR 500<br><sub>Internal Server Error</sub></span>
            <br><br><br><br>
            <span style="color: #CC0000; font-size: 20pt; font-weight: lighter;">Der Server hat einen internen Fehler oder eine Fehlkonfiguration festgestellt und konnte Ihre Anfrage nicht abschlie&szlig;en.</span>
            <br><br>
            <span style="color: #CC0000; font-size: 15pt; font-weight: lighter;">Wenden Sie sich bitte an den Server-Administrator, [tobias.hattinger@gmx.at] und informieren Sie ihn &uuml;ber den Zeitpunkt des Auftretens des Fehlers und alle weiteren Aktionen Ihrerseits, die m&ouml;glicherweise zu diesen Fehler gef&uuml;hrt haben.</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur&uuml;ck zur letzten Seite</button></a>
        </center>
    ';

    include("footer.php");
?>