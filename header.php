<?php
    session_start();
    require("data/mysql_connect.php");
    require("data/basefunctions.php");
    require("data/functions.php");


    $revision = 1;

    $title="O&Ouml;. Badmintonverband";

    echo '
        <!DOCTYPE html>
            <html>
                <head>

                    <link rel="stylesheet" type="text/css" href="css/style.css?'.$revision.'">
                    <link href="content/favicon.png" rel="icon" type="image/x-icon" />
                    <script src="/data/source.js?'.$revision.'"></script>
                    <title> '.$title.' </title>
                <head>
                <body>

                    <header>

                    </header>
                    <nav>

                    </nav>
    ';

?>