<?php
    require("header.php");
    PageTitle("News");

    echo '<h1 class="stagfade1">News</h1>

    '.PageContent('1',CheckPermission("ChangeContent")).'
    ';

    include("footer.php");
?>