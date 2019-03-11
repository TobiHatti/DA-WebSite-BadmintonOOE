<?php
    require("header.php");
    PageTitle("Nachwuchs-Ranglisten");

    echo '<h1 class="stagfade1">Nachwuchs-Ranglisten</h1>';

    echo PageContent('1',CheckPermission("ChangeContent"));

    include("footer.php");
?>