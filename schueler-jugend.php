<?php
    require("header.php");
    PageTitle("Sch\u00fcler / Jugend");

    echo '<h1 class="stagfade1">Sch&uuml;ler / Jugend</h1>

    <p>
       '.PageContent('1',CheckPermission("ChangeContent")).'
    </p>




    ';

    include("footer.php");
?>