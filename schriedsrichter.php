<?php
    require("header.php");
    PageTitle("Schiedsrichter");

    echo '<h1 class="stagfade1">News</h1>

      '.PageContent('1').'
      '.PageContent('2').'

    <br>
    <br>
      '.PageContent('3').'

    ';

    include("footer.php");
?>