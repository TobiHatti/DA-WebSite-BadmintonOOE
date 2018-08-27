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

        <h2>Pager</h2>
        ';

        $entriesPerPage = 10;
        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        // sql-query
        $strSQL = "SELECT * FROM ..... LIMIT $offset,$entriesPerPage";

        echo Pager("SELECT * FROM ......",$entriesPerPage);

        echo '



    ';

    include("footer.php");
?>