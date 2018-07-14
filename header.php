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
<<<<<<< HEAD
                    <link rel="stylesheet" type="text/css" href="css/style.css?2323">
                    <link rel="stylesheet" type="text/css" href="css/designer.css?2323">
                    <link rel="stylesheet" type="text/css" href="css/flags.css?2323">
                    <link href="content/favicon.png" rel="icon" type="image/x-icon" />
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <script src="/data/source.js?2323"></script>

                       &copy;
                       &Ouml;
                       &szlig;

=======
                    <link rel="stylesheet" type="text/css" href="css/style.css?'.$revision.'">
                    <link href="files/content/favicon.ico" rel="icon" type="image/x-icon" />
                    <script src="/data/source.js?'.$revision.'"></script>
                <head>
                <body>
>>>>>>> b22f62b1275c0633ccec353857189d312661ebcf
                    <header>

                    </header>
                    <nav>

                    </nav>
    ';

?>