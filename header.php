<?php
    session_start();
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');

    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");
    require("data/editfunctions.php");
    require("data/multipagepost.php");

    //MySQLPDSave("d");



    $revision = 3;

    echo '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>O&Ouml;. Badmintonverband</title>

                <!-- Own links -->
                    <link rel="stylesheet" type="text/css" href="/css/style.css?'.$revision.'">
                    <link rel="stylesheet" type="text/css" href="/css/layout_modern.css">
                    <link rel="stylesheet" type="text/css" href="/css/menu.css?'.$revision.'">
                    <link rel="stylesheet" type="text/css" href="/css/slide.css?'.$revision.'" />
                    <link href="/content/favicon.png" rel="icon" type="image/x-icon" />
                <!-- End own links -->


                <!-- Own Scripts -->
                    <script src="/js/source.js?'.$revision.'"></script>
                <!-- End of Own Scripts -->


                <!-- External Links/Scripts -->
                    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
                <!-- End of External Links/Scripts -->


                <!-- Froala-Texteditor -->
                    <link rel="stylesheet" href="/css/froala/froala_editor.css">
                    <link rel="stylesheet" href="/css/froala/froala_style.css">
                    <link rel="stylesheet" href="/css/froala/plugins/code_view.css">
                    <link rel="stylesheet" href="/css/froala/plugins/colors.css">
                    <link rel="stylesheet" href="/css/froala/plugins/emoticons.css">
                    <link rel="stylesheet" href="/css/froala/plugins/image_manager.css">
                    <link rel="stylesheet" href="/css/froala/plugins/image.css">
                    <link rel="stylesheet" href="/css/froala/plugins/line_breaker.css">
                    <link rel="stylesheet" href="/css/froala/plugins/table.css">
                    <link rel="stylesheet" href="/css/froala/plugins/char_counter.css">
                    <link rel="stylesheet" href="/css/froala/plugins/video.css">
                    <link rel="stylesheet" href="/css/froala/plugins/fullscreen.css">
                    <link rel="stylesheet" href="/css/froala/plugins/file.css">
                    <link rel="stylesheet" href="/css/froala/plugins/quick_insert.css">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
                <!-- End of Froala -->

                <!-- jsColor-->
                    <script src="/js/jscolor.js"></script>
                <!-- End of jsColor -->

                <!-- HTML2Canvas-->
                    <script src="/js/html2canvas.js"></script>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://html2canvas.hertzen.com/build/html2canvas.js"></script>
                <!-- End of HTML2Canvas -->

                <!-- File Buttons -->
                    <!--[if IE]>
                    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
                    <![endif]-->
                    <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
                <!-- End of File Buttons -->


                <!-- Header-Parallax -->
                    <script>
                        window.addEventListener("scroll", function(e) {
                            var scrOffset = (window.scrollY/3) + 360;
                            document.getElementById("htmlheader").style.backgroundPosition = "0px " + (scrOffset) + "px";
                        });
                    </script>
                <!-- End of Header-Parallax -->


            </head>

            <body>

                ';

                if(GetProperty("EnablePreloader"))
                {
                    echo '
                        <script>
                            window.addEventListener("load", function(){
                            	document.getElementById("loader-wrapper").remove();
                            });

                            setTimeout(function() { document.getElementById("loader_msg").value="Lade." }, 800);
                            setTimeout(function() { document.getElementById("loader_msg").value="Lade.." }, 1600);
                            setTimeout(function() { document.getElementById("loader_msg").value="Lade..." }, 2400);
                        </script>

                        <div id="loader-wrapper">
                            <h3><output id="loader_msg">Lade</output></h3>
                            <br><br><br><br><br>
                            <center><div id="loader"></div></center>
                        </div>
                    ';
                }

                echo '
                <header id="htmlheader">
                    <div class="header_logo"></div>
                    <!-- <div class="header_sponsor"></div> -->
                    ';

                    if(isset($_SESSION['userID']))
                    {
                        echo '<div class="quickNav"><img src="/content/favicon.png" alt="" style="margin-bottom: -3px; margin-right: 8px;"/>';


                        if(CheckPermission("AddNews")) echo '<a href="/news/neu">News hinzuf&uuml;gen</a> | ';
                        if(CheckPermission("AddGallery")) echo '<a href="/fotogalerie/neu">Galerie hinzuf&uuml;gen</a> | ';
                        if(CheckPermission("AddDate")) echo '<a href="/kalender/neu">Termin hinzuf&uuml;gen</a> | ';

                        echo '<span style="float:right">Angemeldet als '.$_SESSION['username'].' - <a href="/logout"><u>Abmelden</u></a></span></div>';
                    }
                    echo '
                </header>

                <nav>
                    <div class="dropdown_menu_container">
                        <div id="cssmenu">
                            <ul>
                                <li><a href="/" style="cursor: pointer">Startseite</a></li>

                                <li class="active" style="cursor: default;"><a href="#">Verband</a>
                                    <ul>
                                        <li><a href="/vorstand">Vorstand</a></li>
                                        <li><a href="/satzung">Satzung & Ordnungen</a></li>
                                        <li><a href="/vereine">Vereine</a></li>
                                        <li><a href="/links">Links</a></li>
                                        <li><a href="/downloads">Downloads</a></li>
                                    </ul>
                                </li>

                                <li class="active"><a href="#">Spielbetrieb</a>
                                    <ul>
                                        <li><a href="#" style="cursor: default">O&Ouml;MM <span style="float:right; color: #FFFFFF;">&#10148;</span></a>
                                            <ul>
                                                <li><a href="/allgemeine-klasse">Allg. Klasse</a></li>
                                                <li><a href="/schueler-jugend">Sch&uuml;ler / Jugend</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" style="cursor: default">Ranglisten <span style="float:right; color: #FFFFFF;">&#10148;</span></a>
                                            <ul>
                                                <li><a href="/ooebv-ranglisten.php">O&Ouml;BV-Ranglisten</a></li>
                                                <li><a target="_blank" href="http://www.badminton.at">&Ouml;BV-Ranglisten</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/zentralausschreibung">Zentralausschreibung</a></li>
                                        <li><a href="/senioren">Senioren</a></li>
                                        <li><a href="/schriedsrichter">Schiedsrichter</a></li>
                                    </ul>
                                </li>

                                <li class="active"><a href="#">Nachwuchs</a>
                                    <ul>
                                        <li><a href="/nachwuchskader">O&Ouml; Nachwuchskader</a></li>
                                        <li><a href="/trainingszeiten">Trainingszeiten BNLZ-Nord</a></li>
                                        <li><a href="/fotogalerie">Fotogalerie</a></li>
                                    </ul>
                                </li>

                                <li class="active"><a href="#">Archiv</a>
                                    <ul>
                                        <li><a href="/jahresberichte">Jahresberichte</a></li>
                                        <li><a href="/news-archiv">News-Archiv</a></li>
                                        <li><a href="/ooemm-archiv">O&Ouml;MM-Archiv</a></li>
                                    </ul>
                                </li>

                                ';

                                if(isset($_SESSION['userID']))   // Condition: Check if user can edit any element of the page
                                {
                                    echo '
                                        <li class="active"><a href="#">Verwaltung</a>
                                            <ul>
                                                <li><a style="width: 200px;" href="/news/neu">Neuer News-Artikel</a></li>
                                                <li><a style="width: 200px;" href="/fotogalerie/neu">Neue Fotogalerie</a></li>
                                                <li><a style="width: 200px;" href="/zentralausschreibung/neu">Neue Zentralausschreibung</a></li>
                                                <li><a style="width: 200px;" href="/kalender/neu">Neuen Termin anlegen</a></li>
                                                <li><a style="width: 200px;" href="/settings"><hr>Seiten-Einstellungen</a></li>
                                            </ul>
                                        </li>
                                    ';
                                }

                                echo '

                            </ul>

                            <!-- Standard Search-Bar -->

                            <div class="searchbar_container">
                                <form action="/suche" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                    <input class="searchbar" name="searchVal" type="search" placeholder="Suchen...">
                                </form>
                            </div>


                            <!-- Mobile Search-Bar -->
                            <div class="mobile_searchtrigger_container">
                                <a href="#searchpop" onclick="bgenScroll();"><button class="searchbar_trigger"><center><img src="/content/search.png" style="height:22px;" alt="" /></center></button></a>
                            </div>

                        </div>

                        <!-- Search-Bar Mobile version -->
                        <div class="mobile_searchbar_container" id="searchpop">
                            <form action="/suche" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <div style="vertical-align:top; display:block;"><input name="searchVal" type="search" class="mobile_searchbar"/><a href="#"><img src="/content/cross.png" style="height:18px;margin-bottom:-3px;margin-left: 8px;" alt="" /></a></div>
                            </form>
                        </div>
                    </div>
                </nav>
                <main>
    ';

?>