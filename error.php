<?php
    require("header.php");

    echo '
        <center>
            <br><br>
            <span style="color: #CC0000; font-size: 40pt; font-weight: lighter;">'.$_SESSION['ExNotificationTitle'].'</span>
            <br><br>
            <img src="'.$_SESSION['ExNotificationIcon'].'" alt="" style="width: 150px;"/><br><br>
            <span style="color: #CC0000; font-size: 25pt; font-weight: lighter;">'.$_SESSION['ExNotificationMessage'].'</span>
            <br><br><br>
            <a href="javascript:history.back()"><button type="button" style="font-size: 18pt;">Zur&uuml;ck zur letzten Seite</button></a>
        </center>
    ';

    include("footer.php");
?>