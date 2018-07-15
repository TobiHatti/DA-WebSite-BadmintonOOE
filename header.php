<?php
    session_start();
    require("data/mysql_connect.php");
    require("data/basefunctions.php");
    require("data/functions.php");

    $revision = 2;

    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <link rel="stylesheet" type="text/css" href="css/style.css?'.$revision.'">
                <link rel="stylesheet" type="text/css" href="css/menu.css?'.$revision.'">
                <link href="content/favicon.png" rel="icon" type="image/x-icon" />
                <script src="/data/source.js?'.$revision.'"></script>
                <script src="/data/menu.js?'.$revision.'"></script>


                <!-- Start WOWSlider.com HEAD section --> <!-- add to the <head> of your page -->
                <link rel="stylesheet" type="text/css" href="data/style.css" />
                <script type="text/javascript" src="data/jquery.js"></script>
                <!-- End WOWSlider.com HEAD section -->


                <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
                <title>O&Ouml;. Badmintonverband</title>
            </head>

            <body>

                <header>
                    <img src="/content/logo.png" alt="O&Ouml;. Badminton-Verband" class="header_logo"/>
                    <img src="/content/babolat.png" alt="" class="header_sponsor"/>
                </header>


                <nav>
                    <div id="cssmenu">
                        <ul>
                            <li><a href="#">Startseite</a></li>

                            <li class="active"><a href="#">Verband</a>
                                <ul>
                                    <li><a href="#">Vorstand</a></li>
                                    <li><a href="#">Satzung & Ordnungen</a></li>
                                    <li><a href="#">Vereine</a></li>
                                    <li><a href="#">Links</a></li>
                                    <li><a href="#">Downloads</a></li>
                                </ul>
                            </li>

                            <li class="active"><a href="#">Spielbetrieb</a>
                                <ul>
                                    <li><a href="#">O&Ouml;MM <span style="float:right;">&#10148;</span></a>
                                        <ul>
                                            <li><a href="#">Allg. Klasse</a></li>
                                            <li><a href="#">Sch&uuml;ler / Jugend</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Ranglisten <span style="float:right;">&#10148;</span></a>
                                        <ul>
                                            <li><a href="#">O&Ouml;BV-Ranglisten</a></li>
                                            <li><a href="#">&Ouml;BV-Ranglisten</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Zentralausschreibung</a></li>
                                    <li><a href="#">Senioren</a></li>
                                    <li><a href="#">Schiedsrichter</a></li>
                                </ul>
                            </li>

                            <li class="active"><a href="#">Nachwuchs</a>
                                <ul>
                                    <li><a href="#">O&Ouml; Nachwuchskader</a></li>
                                    <li><a href="#">Trainingszeiten BNLZ-Nord</a></li>
                                    <li><a href="#">Fotogalerie</a></li>
                                </ul>
                            </li>

                            <li class="active"><a href="#">Archiv</a>
                                <ul>
                                    <li><a href="#">Jahresberichte</a></li>
                                    <li><a href="#">News-Archiv</a></li>
                                    <li><a href="#">O&Ouml;MM-Archiv <span style="float:right;">&#10148;</span></a>
                                        <ul>
                                            <li><a href="#">2005/2006</a></li>
                                            <li><a href="#">2004/2005</a></li>
                                            <li><a href="#">2003/2004</a></li>
                                            <li><a href="#">2002/2003</a></li>
                                            <li><a href="#">2001/2002</a></li>
                                            <li><a href="#">2000/2001</a></li>
                                            <li><a href="#">1999/2000</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
    ';

?>