<?php
    require("header.php");

    echo '
        <center>
            <span style="color: #CC0000; font-size: 50pt; font-weight: lighter;">ERROR 404</span>
            <br><br>
            <span style="color: #CC0000; font-size: 30pt; font-weight: lighter;">Ein Fehler ist aufgetreten.</span>
            <br><br>
            <span style="color: #CC0000; font-size: 20pt; font-weight: lighter;">Die Seite nach der Sie gesucht haben existiert nicht.</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur&uuml;ck zur letzten Seite</button></a>
        </center>
    ';

    include("footer.php");
?>