<?php
    require("header.php");
    PageTitle("OÖBV-Ranglisten");

    echo '<h1 class="stagfade1">OÖBV-Ranglisten</h1>';

    echo PageContent('1',CheckPermission("ChangeContent"));

    include("footer.php");
?>