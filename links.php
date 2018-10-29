<?php
    require("header.php");
    PageTitle("Links");

    echo '<h1 class="stagfade1">Links</h1>

        <p>
           '.PageContent('1',CheckPermission("ChangeContent")).'
        </p>

    ';



    include("footer.php");
?>