<?php
    require("header.php");
    PageTitle("Senioren");

    echo '<h1 class="stagfade1">Senioren</h1>


    '.PageContent('1',CheckPermission("ChangeContent")).'

    ';

    include("footer.php");
?>