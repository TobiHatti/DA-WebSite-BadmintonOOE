<?php
    require("header.php");
    PageTitle("Allgemeine Klasse");

    echo '
        <h1 class="stagfade1">Allgemeine Klasse</h1>
        <hr>
    ';

    if(CheckPermission("EditSpielerrangliste")) echo '<u><a href="/spielerrangliste/einstellungen">&#x270E; Spielerranglisten-Einstellungen</a></u><br><br>'; 

    echo '
        <p>
        '.PageContent('1',CheckPermission("ChangeContent")).'<br>
        </p>

    ';




    for($i=intval(date("Y"));$i>=2011;$i--)
    {
        $year = $i.'-'.($i+1);
        $lastUpdateSett = "Y".$year."LastUpdate";
        $lastChangeDate = MySQL::Scalar("SELECT value FROM ranglisten_settings WHERE setting = ?",'s',$lastUpdateSett);
        $lastChange = str_replace('ä','&auml;',strftime("%d.%m.%Y",strtotime($lastChangeDate)));

        if($i == intval(date("Y")))
        {
            // Saisonwechsel mit 1. September
            if(intval(date("m"))>= 9) echo '<p>'.PageContent($i,CheckPermission("ChangeContent")).'<a href="spielerrangliste/'.$i.'-'.($i+1).'/alle">Spielerrangliste '.$i.'-'.($i+1).' (Stand: '.$lastChange.')</a></p> ';
        }
        else echo '<p>'.PageContent($i,CheckPermission("ChangeContent")).'<a href="spielerrangliste/'.$i.'-'.($i+1).'/alle">Spielerrangliste '.$i.'-'.($i+1).' (Stand: '.$lastChange.')</a></p> ';
    }

    echo '




    <hr>


    ';

    include("footer.php");
?>