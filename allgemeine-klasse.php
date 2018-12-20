<?php
    require("header.php");
    PageTitle("Allgemeine Klasse");

    echo '<h1 class="stagfade1">Allgemeine Klasse</h1>
    <hr>
    ';

    if(CheckPermission("EditSpielerrangliste"))
    {
        echo '<u><a href="/spielerrangliste/einstellungen">&#x270E; Spielerranglisten-Einstellungen</a></u><br><br>';
    }

    for($i=intval(date("Y"));$i>=2011;$i--)
    {
        if($i == intval(date("Y")))
        {
            // Saisonwechsel mit 1. September
            if(intval(date("m"))>= 9) echo '<p><a href="spielerrangliste/'.$i.'-'.($i+1).'/alle">Spielerrangliste '.$i.'-'.($i+1).'</a></p> ';
        }
        else echo '<p><a href="spielerrangliste/'.$i.'-'.($i+1).'/alle">Spielerrangliste '.$i.'-'.($i+1).'</a></p> ';
    }

    echo '




    <hr>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>
    ';

    include("footer.php");
?>