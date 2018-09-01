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
                <table class="settingTable">
                    '.SettingOption("N","SliderImageCount", "Anzahl an Bildern die bei Slider auf Startseite angezeigt werden", "post-name", "sopt".$i++ , GetProperty("SliderImageCount")).'
                    '.SettingOption("C","EnablePreloader", "Preloader Aktivieren", "post-name", "sopt".$i++ , GetProperty("EnablePreloader")).'
                    '.SettingOption("C","EnablePreloaderArchive", "Preloader Aktivieren im News-Archiv", "post-name", "sopt".$i++ , GetProperty("EnablePreloaderArchive")).'
                    '.SettingOption("N","PagerSizeNews", "Pager-Gr&ouml;&szlig;e bei News-Listen", "post-name", "sopt".$i++ , GetProperty("PagerSizeNews")).'
                    '.SettingOption("N","PagerSizeGalleryAlbum", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Alben", "post-name", "sopt".$i++ , GetProperty("PagerSizeGalleryAlbum")).'
                    '.SettingOption("N","PagerSizeGalleryImage", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Fotos", "post-name", "sopt".$i++ , GetProperty("PagerSizeGalleryImage")).'
                    '.SettingOption("N","NewsAmountStartpageTN", "Menge an Artikeln die auf der Startseite unter \"Neuigkeiten\" angezeigt werden", "post-name", "sopt".$i++ , GetProperty("NewsAmountStartpageTN")).'
                    '.SettingOption("N","NewsAmountStartpageNW", "Menge an Artikeln die auf der Startseite unter \"Nachwuchs\" angezeigt werden", "post-name", "sopt".$i++ , GetProperty("NewsAmountStartpageNW")).'
                    '.SettingOption("N","NewsAmountTile", "Menge an Artikeln die in der Seitenleiste unter \"Neueste Beitr&auml;ge\" angezeigt werden", "post-name", "sopt".$i++ , GetProperty("NewsAmountTile")).'
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
        else if($_GET['topic'] == 'Permissions')
        {
            echo '
                <table>
                    <tr>
                        <td><b>ChangeContent</b></td>
                        <td>Seiteninhalte verwalten/&auml;ndern</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>ManageSettings</b></td>
                        <td>Seiteneinstellungen verwalten/&auml;ndern</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>ManageSponsors</b></td>
                        <td>Sponsorenlisten verwalten/&auml;ndern</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>ManageUsers</b></td>
                        <td>Nutzer hinzuf&uuml;gen/entfernen</td>
                    </tr>
                    <tr>
                        <td><b>ManagePermissions</b></td>
                        <td>Nutzer-Rechte verwalten</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>AddGallery</b></td>
                        <td>Neue Galerie erstellen/Fotos hochladen</td>
                    </tr>
                    <tr>
                        <td><b>EditGallery</b></td>
                        <td>Galerie bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteGallery</b></td>
                        <td>Album/Fotos l&ouml;schen</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>AddNews</b></td>
                        <td>Artikel hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditNews</b></td>
                        <td>Artikel bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteNews</b></td>
                        <td>Artikel l&ouml;schen</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                </table>
            ';
        }
    }

    echo '

                </div>
            </body>
        </html>
    ';

?>