<?php
    require("header.php");
    PageTitle("Allgemeine Klasse");

    echo '<h1 class="stagfade1">Allgemeine Klasse</h1>
    <hr>
    ';

    for($i=date("Y");$i>=2011;$i--)
    {
        echo '<p><a href="spielerrangliste/'.$i.'-'.($i+1).'/alle">Spielerrangliste '.$i.'-'.($i+1).'</a></p> ';
    }

    echo '




    <hr>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>
    ';

    include("footer.php");
?>