<?php
    require("header.php");
    PageTitle("Schiedsrichter");

    echo '<h1 class="stagfade1">News</h1>

      '.PageContent('1',CheckPermission("ChangeContent")).'

    ';



    include("footer.php");
?>