<?php
    require("header.php");

    // Forcing by: url/%

    echo '
        <center>
            <span style="color: #CC0000; font-size: 50pt; font-weight: lighter;">ERROR 401<br><sub>Bad Request</sub></span>
            <br><br><br><br>
            <span style="color: #CC0000; font-size: 30pt; font-weight: lighter;">Ein Fehler ist aufgetreten.</span>
            <br><br>
            <span style="color: #CC0000; font-size: 20pt; font-weight: lighter;">Ihr Browser (oder Proxy) hat eine ung&uuml;ltige Anfrage gesendet, die vom Server nicht beantwortet werden kann.</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur&uuml;ck zur letzten Seite</button></a>
        </center>
    ';

    include("footer.php");
?>