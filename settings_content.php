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
    ';
        require("headerlinks.php");

    echo '
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
                <b>Entwicklernotiz: Kategorien noch richtig einordnen!</b>
                <br><br>

                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table class="settingTable">
                        '.SettingOption("N","SliderImageCount", "Anzahl an Bildern die bei Slider auf Startseite angezeigt werden", "SliderImageCount", "sopt".$i++).'
                        '.SettingOption("C","EnablePreloader", "Preloader Aktivieren", "EnablePreloader", "sopt".$i++).'
                        '.SettingOption("C","EnablePreloaderArchive", "Preloader Aktivieren im News-Archiv", "EnablePreloaderArchive", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeNews", "Pager-Gr&ouml;&szlig;e bei News-Listen", "PagerSizeNews", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeGalleryAlbum", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Alben", "PagerSizeGalleryAlbum", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeGalleryImage", "Pager-Gr&ouml;&szlig;e bei Fotogalerie-Fotos", "PagerSizeGalleryImage", "sopt".$i++).'
                        '.SettingOption("N","PagerSizeCalendar", "Pager-Gr&ouml;&szlig;e bei Kalender (Liste)", "PagerSizeCalendar", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountStartpageTN", "Menge an Artikeln die auf der Startseite unter \"Neuigkeiten\" angezeigt werden", "NewsAmountStartpageTN", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountStartpageNW", "Menge an Artikeln die auf der Startseite unter \"Nachwuchs\" angezeigt werden", "NewsAmountStartpageNW", "sopt".$i++).'
                        '.SettingOption("N","NewsAmountTile", "Menge an Artikeln die in der Seitenleiste unter \"Neueste Beitr&auml;ge\" angezeigt werden", "NewsAmountTile", "sopt".$i++).'
                        '.SettingOption("S","SliderAnimation", "Slider-Animation auf Startseite", "SliderAnimation", "sopt".$i++,$sliderAnimations).'
                        '.SettingOption("C","ShowZAinAG", "Zentralausschreibungen in Terminplaner anzeigen", "ShowZAinAG", "sopt".$i++).'
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
            if(isset($_GET['user']))
            {
                $uDat = FetchArray("users","id",$_GET['user']);
                echo '<h3>Nutzerdaten von <i>'.$uDat['firstname'].' '.$uDat['lastname'].'</i></h3>';

                echo '<i>List Permissions here</i>';
            }
            else if(isset($_GET['register']))
            {
                echo '
                    <h2>Nutzer eintragen</h2>
                    <br>

                    <h3>Allgemeines</h3>
                    <hr>
                    <center>
                        <table>
                            <tr>
                                <td class="ta_r">Anrede:</td>
                                <td>'.RadioButton("Herr","sex",1).'</td>
                                <td>'.RadioButton("Frau","sex").'</td>
                            </tr>
                            <tr>
                                <td class="ta_r">Vorname: </td>
                                <td colspan=2><input type="text" placeholder="Vorname"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Nachname: </td>
                                <td colspan=2><input type="text" placeholder="Nachname"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">E-Mail: </td>
                                <td colspan=2><input type="text" placeholder="E-Mail"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Passwort: </td>
                                <td colspan=2><input type="text" placeholder="Passwort"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">(Wiederholen) Passwort: </td>
                                <td colspan=2><input type="text" placeholder="Passwort"/></td>
                            </tr>
                        </table>
                    </center>
                    <h3>Rechte</h3>
                    <hr>

                    <center>

                        <table style="width: 60%;">
                            <tr><td colspan=6><h4>Generelles</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1e").'</td>
                                <td>Inhalte &auml;ndern</td>
                                <td style="width: 50px;">'.Checkbox("2","2e").'</td>
                                <td>Einstellungen &auml;ndern</td>
                                <td style="width: 50px;">'.Checkbox("3","3e").'</td>
                                <td>Sponsoren &auml;ndern</td>
                            </tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1f").'</td>
                                <td>Nutzer verwalten</td>
                                <td style="width: 50px;">'.Checkbox("2","2f").'</td>
                                <td>Rechte verwalten</td>
                            </tr>
                        </table>
                        <br><br>
                        <table style="width: 50%;">
                            <tr><td colspan=6><h4>News</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1").'</td>
                                <td>Erstellen</td>
                                <td style="width: 50px;">'.Checkbox("2","2").'</td>
                                <td>Bearbeiten</td>
                                <td style="width: 50px;">'.Checkbox("3","3").'</td>
                                <td>L&ouml;schen</td>
                            </tr>
                        </table>
                        <br>
                        <table style="width: 50%;">
                            <tr><td colspan=6><h4>Zentralausschreibungen</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1a").'</td>
                                <td>Erstellen</td>
                                <td style="width: 50px;">'.Checkbox("2","2a").'</td>
                                <td>Bearbeiten</td>
                                <td style="width: 50px;">'.Checkbox("3","3a").'</td>
                                <td>L&ouml;schen</td>
                            </tr>
                        </table>
                        <br>
                        <table style="width: 50%;">
                            <tr><td colspan=6><h4>Termine</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1b").'</td>
                                <td>Erstellen</td>
                                <td style="width: 50px;">'.Checkbox("2","2b").'</td>
                                <td>Bearbeiten</td>
                                <td style="width: 50px;">'.Checkbox("3","3b").'</td>
                                <td>L&ouml;schen</td>
                            </tr>
                        </table>
                        <br>
                        <table style="width: 50%;">
                            <tr><td colspan=6><h4>Fotogalerie</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1c").'</td>
                                <td>Erstellen</td>
                                <td style="width: 50px;">'.Checkbox("2","2c").'</td>
                                <td>Bearbeiten</td>
                                <td style="width: 50px;">'.Checkbox("3","3c").'</td>
                                <td>L&ouml;schen</td>
                            </tr>
                        </table>
                        <br>
                        <table style="width: 50%;">
                            <tr><td colspan=6><h4>Vorstand</h4></td></tr>
                            <tr>
                                <td style="width: 50px;">'.Checkbox("1","1d").'</td>
                                <td>Erstellen</td>
                                <td style="width: 50px;">'.Checkbox("2","2d").'</td>
                                <td>Bearbeiten</td>
                                <td style="width: 50px;">'.Checkbox("3","3d").'</td>
                                <td>L&ouml;schen</td>
                            </tr>
                        </table>
                        <br>


                    </center>


                ';
            }
            else
            {
                echo '
                    <h3>Neuen Nutzer eintragen</h3>
                    <hr>
                    <a href="/settings_content?topic=Nutzer&register"><button type="button">Nutzer Registrieren</button></a>

                    <br><br>
                    <h3>Aktuelle Nutzer</h3>
                    <hr>
                    <ul>
                ';

                $strSQL = "SELECT * FROM users";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '<a href="/settings_content?topic=Nutzer&user='.$row['id'].'"><li>'.$row['firstname'].' '.$row['lastname'].' <span style="color: #696969">['.$row['email'].']</span></li></a>';
                }
                echo '</ul>';
            }
        }
        else if($_GET['topic'] == 'Startseite')
        {
            echo '

            ';
        }
        else if($_GET['topic'] == 'Rechte')
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
                    <tr>
                        <td><b>AddVorstand</b></td>
                        <td>Vorstand-Mitglied hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditVorstand</b></td>
                        <td>Vorstand-Mitglied bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteVorstand</b></td>
                        <td>Vorstand-Mitglied l&ouml;schen</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>AddDate</b></td>
                        <td>Termin hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditDate</b></td>
                        <td>Termin bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteDate</b></td>
                        <td>Termin l&ouml;schen</td>
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