<?php
    require("header.php");
    PageTitle("Allgemeine Klasse");

    echo '<h1 class="stagfade1">Allgemeine Klasse</h1>


    <a href="spielerrangliste/2017-2018">Spielerrangliste 2017-2018</a>



    <hr>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>
    ';

    include("footer.php");
?>