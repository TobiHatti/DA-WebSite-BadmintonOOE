<?php
    require("header.php");

    echo '
        <center>
            <img src="/content/downloading.gif" alt="" style="height:300px;"/><br>
            <span style="font-size: 30pt; font-weight: lighter;">Download wird durchgeführt...</span>
            <br><br>
            <span style="font-size: 20pt; font-weight: lighter;">Bitte warten Sie bis der Download der Datei gestartet hat...</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur letzten Seite zur&uuml;ckkehren</button></a>
        </center>
    ';

    include("footer.php");
?>