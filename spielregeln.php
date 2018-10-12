<?php
    require("header.php");
    PageTitle("Schiedsrichter");

    echo '<h1 class="stagfade1">Spielregeln</h1>';

    echo PageContent('1',CheckPermission("ChangeContent"));


    include("footer.php");
?>