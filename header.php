<?php
    session_start();
    require("data/mysql_connect.php");
    require("data/basefunctions.php");
    require("data/functions.php");


    $revision = 1;

    echo '
        <!DOCTYPE html>
            <html>
                <head>
                    <link rel="stylesheet" type="text/css" href="css/style.css?'.$revision.'">
                    <link href="files/content/favicon.ico" rel="icon" type="image/x-icon" />
                    <script src="/data/source.js?'.$revision.'"></script>
                <head>
                <body>
                    <header>

                    </header>
                    <nav>

                    </nav>
    ';

?>