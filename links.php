<?php
    require("header.php");
    PageTitle("Links");

    echo '<h1 class="stagfade1">Links</h1>

    <div class="links_container">
        <p>
           '.PageContent('1',CheckPermission("ChangeContent")).'
        </p>
    </div>


    <div class="links_iframe_container">
        <iframe src="" frameborder="0" id="chframe" class="links_iframe"></iframe>
    </div>


    ';



    include("footer.php");
?>