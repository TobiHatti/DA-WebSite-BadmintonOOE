<?php
    require("header.php");

    echo '
        <h1 class="stagfade1">Templates</h1>
        <hr>

        <h2>Loader</h2>

        '.Loader().'

        <br>
        <center>
            <button type="button" onclick="LoadAnimation();">Submit</button>
        </center>

        <hr>

    ';

    include("footer.php");
?>