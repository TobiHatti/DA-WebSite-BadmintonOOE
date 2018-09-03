<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");


    if(isset($_POST['updateSetting']))
    {
        // Update Property
        if($_POST[$_POST['updateSetting'].'_inputType'] == "C")
        {
            $checked = (isset($_POST[$_POST['updateSetting'].'_val'])) ? 1 : 0;
            SetProperty($_POST['updateSetting'],  $checked);
        }
        else SetProperty($_POST['updateSetting'],  $_POST[$_POST['updateSetting'].'_val']);


        // Additional operation (special Cases)
        if($_POST['updateSetting']=="SliderImageCount") RefreshSliderContent();

        Redirect(ThisPage());
        die();
    }


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
            $sliderAnimations = array();
            $strSQL = "SELECT * FROM slides ORDER BY name ASC";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs)) array_push($sliderAnimations, $row['filename']);

            echo '
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table class="settingTable">
                        '.SettingOption("N","SliderImageCount", "Anzahl an Bildern die bei Slider auf Startseite angezeigt werden", "SliderImageCount", "sopt".$i++).'
                        '.SettingOption("C","EnablePreloader", "Preloader Aktivieren", "EnablePreloader", "sopt".$i++).'
                        '.SettingOption("C","EnablePreloaderArchive", "Preloader Aktivieren im News-Archiv", "EnablePreloaderArchive", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeNews", "Pager-Gr&ouml;&szlig;e bei News-Listen", "PagerSizeNews", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeGalleryAlbum", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Alben", "PagerSizeGalleryAlbum", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeGalleryImage", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Fotos", "PagerSizeGalleryImage", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountStartpageTN", "Menge an Artikeln die auf der Startseite unter \"Neuigkeiten\" angezeigt werden", "NewsAmountStartpageTN", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountStartpageNW", "Menge an Artikeln die auf der Startseite unter \"Nachwuchs\" angezeigt werden", "NewsAmountStartpageNW", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountTile", "Menge an Artikeln die in der Seitenleiste unter \"Neueste Beitr&auml;ge\" angezeigt werden", "NewsAmountTile", "sopt".$i++).'
                        '.SettingOption("S","SliderAnimation", "Slider-Animation auf Startseite", "SliderAnimation", "sopt".$i++,$sliderAnimations).'
                    </table>
                </form>
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
                    <tr>
                        <td><b>AddZA</b></td>
                        <td>Zentralausschreibung hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditZA</b></td>
                        <td>Zentralausschreibung bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteZA</b></td>
                        <td>Zentralausschreibung l&ouml;schen</td>
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