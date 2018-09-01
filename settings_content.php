<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");

    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <link rel="stylesheet" type="text/css" href="/css/style.css">
            </head>
            <body>
                <h2 class="stagfade1">'.((isset($_GET['topic'])) ? $_GET['topic'] : '').'</h2><br>
                <div class="iframe_content stagfade2">
    ';

    $i=0;
    $chbxCorr = 17;

    if(isset($_GET['topic']))
    {
        if($_GET['topic'] == 'Unsorted')
        {
            echo '
                <table>
                    '.SettingOption("N","SliderImageCount", "Anzahl an Bildern die bei Slider auf Startseite angezeigt werden", "post-name", "sopt".$i++ , 3).'
                    '.SettingOption("C","EnablePreloader", "Preloader Aktivieren", "post-name", "sopt".$i++ , 1).'
                    '.SettingOption("C","EnablePreloaderArchive", "Preloader Aktivieren im News-Archiv", "post-name", "sopt".$i++ , 1).'
                    '.SettingOption("N","PagerSizeNews", "Pager-Gr&ouml;&szlig;e bei News-Listen", "post-name", "sopt".$i++ , 10).'
                    '.SettingOption("N","PagerSizeGalleryAlbum", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Alben", "post-name", "sopt".$i++ , 10).'
                    '.SettingOption("N","PagerSizeGalleryImage", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Fotos", "post-name", "sopt".$i++ , 50).'
                    '.SettingOption("N","NewsPeekAmount", "Menge an Artikeln die auf der Startseite unter \"Neuigkeiten\" angezeigt werden", "post-name", "sopt".$i++ , 5).'
                </table>
            ';
        }

        if($_GET['topic'] == 'Allgemein')
        {
            echo '

            ';
        }
        else if($_GET['topic'] == 'Nutzer')
        {
            echo '

            ';
        }
        else if($_GET['topic'] == 'Startseite')
        {
            echo '

            ';
        }
    }

    echo '

                </div>
            </body>
        </html>
    ';

?>