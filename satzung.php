<?php
    require("header.php");
    PageTitle("Satzung & Ordnungen");

    echo '<h1 class="stagfade1">Satzung & Ordnungen</h1>

    <p>
      '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>
    ';

    include("footer.php");
?>