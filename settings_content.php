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

    if(isset($_POST['addUserClubManager']))
    {
        $email = $_POST['email'];
        $password = hash('sha256',hash('sha256',$_POST['password']."salt")."pepper");
        $club = $_POST['club'];

        MySQLNonQuery("INSERT INTO users (id,rank,club,email,password) VALUES ('','clubmanager','$club','$email','$password')");

        Redirect(ThisPage("+newUserAdded"));
        die();
    }

    if(isset($_POST['addUserAdministrative']))
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gedner = $_POST['gender'];
        $email = $_POST['email'];
        $password = hash('sha256',hash('sha256',$_POST['password']."salt")."pepper");

        MySQLNonQuery("INSERT INTO users (id,rank,firstname,lastname,sex,email,password) VALUES ('','administrative','$firstname','$lastname','$gedner','$email','$password')");

        $first = true;
        $uid = MySQLSkalar("SELECT id AS x FROM users WHERE email = '$email' AND password = '$password'");
        $SQLIn = "INSERT INTO permissions (id,user_id,permission,allowed) VALUES ";
        $strSQL = "SELECT * FROM permission_list";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $permission = $row['permission'];
            $allowed = isset($_POST[$permission]) ? 1 : 0;

            // Special Case for ChangeContent
            if($permission == 'DeleteCC' AND isset($_POST['ChangeContent'])) $allowed = 1;

            if($first) $SQLIn .= "('','$uid','$permission','$allowed') ";
            else $SQLIn .= ",('','$uid','$permission','$allowed') ";
            $first = false;
        }

        MySQLNonQuery($SQLIn);

        Redirect(ThisPage("+newUserAdded"));
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
            <body style="font-size:10pt;">
                <div class="iframe_content stagfade1">
                <h2 class="stagfade2">'.((isset($_GET['topic'])) ? $_GET['topic'] : '').'</h2>
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
                echo '<br><h3>Nutzerdaten von <i>'.$uDat['firstname'].' '.$uDat['lastname'].'</i></h3>';

                echo '<i>List Permissions here</i>';
            }
            else if(isset($_GET['administrative']))
            {
                if(isset($_GET['newUserAdded'])) echo '<center><span style="color: #32CD32">Nutzer wurde hinzugef&uuml;gt!</span></center>';

                echo '
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <h2>Nutzer eintragen</h2>
                    <br>

                    <h3>Allgemeines</h3>
                    <hr>
                    <center>
                        <table>
                            <tr>
                                <td class="ta_r">Anrede:</td>
                                <td>'.RadioButton("Herr","gender",1,"M").'</td>
                                <td>'.RadioButton("Frau","gender",0,"W").'</td>
                            </tr>
                            <tr>
                                <td class="ta_r">Vorname: </td>
                                <td colspan=2><input type="text" placeholder="Vorname" name="firstname"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Nachname: </td>
                                <td colspan=2><input type="text" placeholder="Nachname" name="lastname"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">E-Mail: </td>
                                <td colspan=2>
                                    <input type="email" placeholder="E-Mail" name="email" oninput="EMailCheck(this,\'outMailMessage\',\'submitButton\');" required/><br>
                                    <span style="color: red"><output id="outMailMessage"></output></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ta_r">Passwort: </td>
                                <td colspan=2>
                                    <input type="password" placeholder="Passwort" name="password" id="pswd1" oninput="CheckPasswordPair(this,\'pswd2\',\'outPswdMessage\',\'submitButton\')" required/><br>
                                    <span style="color: red"><output id="outPswdMessage"></output></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ta_r">(Wiederholen) Passwort: </td>
                                <td colspan=2><input type="password" placeholder="Passwort" id="pswd2" oninput="CheckPasswordPair(this,\'pswd1\',\'outPswdMessage\',\'submitButton\')" required/></td>
                            </tr>
                        </table>
                    </center>
                    <h3>Rechte</h3>
                    <hr>

                    <center>

                        <table style="width:90%;">
                            <tr>
                                <td style="width: 50%">
                                    <h5>Verwaltung <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Inhalten auf der Seite"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("ChangeContent","ChangeContent",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Seiteninhalte verwalten / &auml;ndern</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("ManageSettings","ManageSettings",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Seiteneinstellungen ver&auml;ndern</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 50%">
                                    <h5>Nutzer <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Nutzern und deren Rechten"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("ManageUsers","ManageUsers",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Nutzer hinzuf&uuml;gen (registrieren) / entfernen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("ManagePermissions","ManagePermissions",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Nutzerrechte verwalten</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table style="width:90%;">
                            <tr>
                                <td style="width: 25%">
                                    <h5>News <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von News-Artikeln"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddNews","AddNews",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditNews","EditNews",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteNews","DeleteNews",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 25%">
                                    <h5>Termine <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Terminen im Kalender"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddDate","AddDate",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditDate","EditDate",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteDate","DeleteDate",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 25%">
                                    <h5>Vereine <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Vereinen"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddClub","AddClub",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditClub","EditClub",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteClub","DeleteClub",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 25%">
                                    <h5>Galerie <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Fotogalerien"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddGallery","AddGallery",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditGallery","EditGallery",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteGallery","DeleteGallery",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table>
                            <tr>
                                <td style="width: 33%">
                                    <h5>Nachwuchskader <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Spielern im Nachwuchskader"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddNWK","AddNWK",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditNWK","EditNWK",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteNWK","DeleteNWK",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 33%">
                                    <h5>Vorstand <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Mitgliedern im Vorstand"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddVorstand","AddVorstand",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditVorstand","EditVorstand",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteVorstand","DeleteVorstand",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 33%">
                                    <h5>Zentralausschreibungen <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Spielern im Nachwuchskader"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddZA","AddZA",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditZA","EditZA",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteZA","DeleteZA",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br><br>
                        <button type="submit" name="addUserAdministrative" id="submitButton">Nutzer Registrieren</button>
                    </center>
                </form>
                ';
            }
            else if(isset($_GET['clubmanager']))
            {
                if(isset($_GET['newUserAdded'])) echo '<center><span style="color: #32CD32">Nutzer wurde hinzugef&uuml;gt!</span></center>';

                echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                            <table>
                                <tr>
                                    <td class="ta_r">E-Mail: </td>
                                    <td colspan=2>
                                        <input type="email" placeholder="E-Mail" name="email" oninput="EMailCheck(this,\'outMailMessage\',\'submitButton\');" required/><br>
                                        <span style="color: red"><output id="outMailMessage"></output></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Passwort: </td>
                                    <td colspan=2>
                                        <input type="password" placeholder="Passwort" name="password" id="pswd1" oninput="CheckPasswordPair(this,\'pswd2\',\'outPswdMessage\',\'submitButton\')" required/><br>
                                        <span style="color: red"><output id="outPswdMessage"></output></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ta_r">(Wiederholen) Passwort: </td>
                                    <td colspan=2><input type="password" placeholder="Passwort" id="pswd2" oninput="CheckPasswordPair(this,\'pswd1\',\'outPswdMessage\',\'submitButton\')" required/></td>
                                </tr>
                                <tr><td colspan=2><br></td></tr>
                                <tr>
                                    <td class="ta_r">Verein:<br>&nbsp;</td>
                                    <td colspan=2>
                                    <select name="club" class="cel_m" required>
                                        <option value="" selected disabled>--- Verein Ausw&auml;hlen ---</option>
                                        ';

                                        $strSQL = "SELECT verein, ort, kennzahl FROM vereine ORDER BY kennzahl ASC";
                                        $rs=mysqli_query($link,$strSQL);
                                        while($row=mysqli_fetch_assoc($rs)) echo '<option value="'.$row['kennzahl'].'">'.$row['kennzahl'].' - '.$row['verein'].' '.$row['ort'].'</option>';

                                        echo '
                                    </select><br>
                                    Verein nicht gefunden? <a href="/vereine/neu" target="_top">Hier neuen Verein anlegen</a>
                                    </td>
                                </tr>
                            </table>
                            <br>

                            Der Nutzer kann nach der ersten Anmeldung das Passwort &auml;ndern

                            <br><br>

                            <button type="submit" name="addUserClubManager" id="submitButton">Vereins-Account erstellen</button>
                        </center>
                    </form>
                ';
            }
            else
            {
                echo '
                    <h3>Neuen Nutzer eintragen</h3>
                    <hr>
                    <a href="/settings_content?topic=Nutzer&administrative"><button type="button">Verwaltungs-Account erstellen</button></a>
                    <a href="/settings_content?topic=Nutzer&clubmanager"><button type="button">Vereins-Account erstellen</button></a>

                    <br><br>
                    <h3>Aktuelle Nutzer</h3>
                    <hr>
                    <h5>Verwaltung:</h5>
                    <ul>
                ';

                $strSQL = "SELECT * FROM users WHERE rank = 'administrative'";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '<a href="/settings_content?topic=Nutzer&user='.$row['id'].'"><li>'.$row['firstname'].' '.$row['lastname'].' <span style="color: #696969">['.$row['email'].']</span></li></a>';
                }
                echo '
                    </ul>
                    <h5>Vereine:</h5>
                    <ul>
                ';

                $strSQL = "SELECT * FROM users INNER JOIN vereine ON users.club = vereine.kennzahl WHERE users.rank = 'clubmanager'";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '<li>'.$row['verein'].' '.$row['ort'].' <span style="color: #696969">['.$row['email'].']</span></li>';
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
                    <tr>
                        <td><b>DeleteCC</b></td>
                        <td>Seiteninhalte l&ouml;schen (Standart bei ChangeContent dabei, ben&ouml;tigt f&uuml;r z.B. editTiles)</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>ManageSettings</b></td>
                        <td>Seiteneinstellungen verwalten/&auml;ndern</td>
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
                    <tr>
                        <td><b>AddClub</b></td>
                        <td>Verein hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditClub</b></td>
                        <td>Verein bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteClub</b></td>
                        <td>Verein l&ouml;schen</td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td><b>AddNWK</b></td>
                        <td>Nachwuchskader hinzuf&uuml;gen</td>
                    </tr>
                    <tr>
                        <td><b>EditNWK</b></td>
                        <td>Nachwuchskader bearbeiten</td>
                    </tr>
                    <tr>
                        <td><b>DeleteNWK</b></td>
                        <td>Nachwuchskader l&ouml;schen</td>
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