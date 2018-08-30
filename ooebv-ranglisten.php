<?php
    require("header.php");
    PageTitle("O\u00d6BV-Ranglisten");

    echo '<h1 class="stagfade1">O&Ouml;BV-Ranglisten</h1>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>



    ';

    include("footer.php");
?>