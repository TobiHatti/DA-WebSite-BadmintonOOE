<?php
    require("header.php");
    PageTitle("Allgemeine Klasse");

    echo '<h1 class="stagfade1">Allgemeine Klasse</h1>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>
    ';

    include("footer.php");
?>