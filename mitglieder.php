<?php
    require("header.php");

    if(CheckRank() == "administrative")
    {
        echo '<h2>Spieler eintragen</h2>';

        echo '<iframe src="/memberAddFrame" frameborder="0" style="width: 100%; height: 480px;"></iframe>'; 
    }

    include("footer.php");
?>