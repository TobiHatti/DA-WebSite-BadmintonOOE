<?php
    require("header.php");

    //Forcing by: <not implemented yet>

    echo '
        <center>
            <span style="color: #CC0000; font-size: 50pt; font-weight: lighter;">ERROR 401<br><sub>Unauthorised</sub></span>
            <br><br><br><br>
            <span style="color: #CC0000; font-size: 30pt; font-weight: lighter;">Ein Fehler ist aufgetreten.</span>
            <br><br>
            <span style="color: #CC0000; font-size: 20pt; font-weight: lighter;">Sie haben keine Rechte diese Seite zu sehen.</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur&uuml;ck zur letzten Seite</button></a>
        </center>
    ';

    include("footer.php");
?>