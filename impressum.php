
<?php
    require("header.php");

    echo'<h1 class="stagfade1">Impressum</h1>';

    echo PageContent("1",CheckPermission("ChangeContent"));

    include("footer.php");
?>
