<?php
    require("header.php");
    PageTitle("Trainingszeiten BNLZ-Nord");

    echo '<h1 class="stagfade1">Trainingszeiten BNLZ-Nord</h1>

    '.PageContent("1",CheckPermission("ChangeContent")).'
    ';

    include("footer.php");
?>