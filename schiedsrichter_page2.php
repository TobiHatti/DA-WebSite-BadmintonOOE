<?php
    require("header.php");
    PageTitle("News");

    echo '<h1 class="stagfade1">News</h1>

    '.PageContent('1').'

    <br>
    <br>

    '.PageContent('2').'

    <p>
        '.PageContent('3').'
    </p>

     '.PageContent('4').'






     <p>
        '.PageContent('5').'
    </p>
    ';

    include("footer.php");
?>