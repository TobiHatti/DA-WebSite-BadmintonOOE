<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    session_start();

    require("headerincludes.php");


    if(isset($_POST['updateSetting']))
    {
        // Update Property
        if($_POST[$_POST['updateSetting'].'_inputType'] == "C")
        {
            $checked = (isset($_POST[$_POST['updateSetting'].'_val'])) ? 1 : 0;
            Setting::Set($_POST['updateSetting'],  $checked);
        }
        else Setting::Set($_POST['updateSetting'],  $_POST[$_POST['updateSetting'].'_val']);


        // Additional operation (special Cases)
        if($_POST['updateSetting']=="SliderImageCount") RefreshSliderContent();

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['addUserClubManager']))
    {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $club = $_POST['club'];

        MySQL::NonQuery("INSERT INTO users (rank,club,email,password) VALUES ('clubmanager',?,?,?)",'@s',$club,$email,$password);

        Redirect(ThisPage("+newUserAdded"));
        die();
    }

    if(isset($_POST['updateUserClubManager']))
    {
        $id = $_POST['updateUserClubManager'];
        $email = $_POST['email'];
        $club = $_POST['club'];

        MySQL::NonQuery("UPDATE users SET club = ?, email = ? WHERE id = ?",'@s',$club,$email,$id);

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['addUserAdministrative']))
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gedner = $_POST['gender'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        MySQL::NonQuery("INSERT INTO users (rank,firstname,lastname,sex,email,password) VALUES ('administrative',?,?,?,?,?)",'@s',$firstname,$lastname,$gedner,$email,$password);

        $first = true;
        $uid = MySQL::Scalar("SELECT id FROM users WHERE email = ? AND password = ?",'@s',$email,$password);
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

        MySQL::NonQuery($SQLIn);

        Redirect(ThisPage("+newUserAdded"));
        die();
    }

    if(isset($_POST['updateUserAdministrative']))
    {
        $id = $_POST['updateUserAdministrative'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gedner = $_POST['gender'];
        $email = $_POST['email'];

        MySQL::NonQuery("UPDATE users SET firstname = ?, lastname = ?, sex = ?, email = ? WHERE id = ?",'@s',$firstname,$lastname,$gedner,$email,$id);

        $strSQL = "SELECT * FROM permission_list";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $permission = $row['permission'];
            $allowed = isset($_POST[$permission]) ? 1 : 0;

            // Special Case for ChangeContent
            if($permission == 'DeleteCC' AND isset($_POST['ChangeContent'])) $allowed = 1;

            if(MySQL::Exist("SELECT * FROM permissions WHERE user_id = ? AND permission = ?",'ss',$id,$permission))
            {
                MySQL::NonQuery("UPDATE permissions SET allowed = ? WHERE user_id = ? AND permission = ?",'@s',$allowed,$id,$permission);
            }
            else MySQL::NonQuery("INSERT INTO permissions (user_id,permission,allowed) VALUES (?,?,?)",'sss',$id,$permission,$allowed);
        }

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['addSponsor']))
    {
        $id = uniqid();
        $name = $_POST['name'];
        $website = $_POST['website'];

        MySQL::NonQuery("INSERT INTO sponsors (id,name,link) VALUES (?,?,?)",'@s',$id,$name,$website);

        FileUpload("content/sponsors/","sponsorLogo","","","UPDATE sponsors SET image = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['updateSponsor']))
    {
        $id = $_POST['updateSponsor'];
        $name = $_POST['name'];
        $website = $_POST['website'];

        MySQL::NonQuery("UPDATE sponsors SET name = ?, link = ? WHERE id = ?",'@s',$name,$website,$id);

        FileUpload("content/sponsors/","sponsorLogo","","","UPDATE sponsors SET image = 'FNAME' WHERE id = '$id'",uniqid());

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
            <body style="font-size:10pt; padding-left: 5px;">
                <div class="iframe_content stagfade1">
                <h2 class="stagfade2">'.((isset($_GET['topic'])) ? str_replace('ss','&szlig;',$_GET['topic']) : '').'</h2>
    ';

    $i=0;
    $chbxCorr = 17;

    if(isset($_GET['topic']))
    {
        if($_GET['topic'] == 'Allgemein')
        {
            echo '
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <br><h4>Preloader</h4>
                    <table class="settingTable">
                        '.SettingOption("C","Preloader Aktivieren (Vorlader)", "EnablePreloader").'
                        '.SettingOption("C","Preloader Aktivieren im News-Archiv", "EnablePreloaderArchive").'
                    </table>

                    <br><h4>Listen-Gr&ouml;&szlig;en</h4>
                    <table class="settingTable">
                        '.SettingOption("N","Listen-Gr&ouml;&szlig;e bei News-Listen", "PagerSizeNews").'
                        '.SettingOption("N","Listen-Gr&ouml;&szlig;e bei Fotogalerie-Alben", "PagerSizeGalleryAlbum").'
                        '.SettingOption("N","Listen-Gr&ouml;&szlig;e bei Fotogalerie-Fotos", "PagerSizeGalleryImage").'
                        '.SettingOption("N","Listen-Gr&ouml;&szlig;e bei Kalender (Liste)", "PagerSizeCalendar").'
                        '.SettingOption("N","Listen-Gr&ouml;&szlig;e bei Suchen", "PagerSizeSearch").'
                        '.SettingOption("N","Menge an Artikeln die in der Seitenleiste unter \"Neueste Beitr&auml;ge\" angezeigt werden", "NewsAmountTile").'
                    </table>

                    <br><h4>Kalender</h4>
                    <table class="settingTable">
                        '.SettingOption("C","Zentralausschreibungen in Terminplaner anzeigen", "ShowZAinAG").'
                    </table>
                </form>
            ';
        }
        else if($_GET['topic'] == 'Startseite')
        {
            $sliderAnimations = array();
            $strSQL = "SELECT * FROM slides ORDER BY name ASC";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs)) array_push($sliderAnimations, $row['filename']);

            echo '
                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                    <br><h4>Felder</h4>
                    <table class="settingTable">
                        '.SettingOption("C","Rundruf-Feld auf Startseite anzeigen", "ShowBroadcast").'
                        '.SettingOption("C","Heutige Termine als Rundruf auf Startseite anzeigen", "ShowTodaysEvents").'
                        '.SettingOption("C","Veranstaltungs-Feld auf Startseite anzeigen", "ShowHomeEvents").'
                    </table>

                    <br><h4>Spieler des Monats</h4>
                    <table class="settingTable">
                        '.SettingOption("C","\"Spieler des Monats\" auf Startseite anzeigen", "ShowSpielerDesMonats").'
                    </table>

                    <br><h4>News</h4>
                    <table class="settingTable">
                        '.SettingOption("N","Menge an Artikeln die auf der Startseite unter \"Neuigkeiten\" angezeigt werden", "NewsAmountStartpageTN").'
                        '.SettingOption("N","Menge an Artikeln die auf der Startseite unter \"Nachwuchs\" angezeigt werden", "NewsAmountStartpageNW").'
                    </table>

                    <br><h4>Slider</h4>
                    <table class="settingTable">
                        '.SettingOption("N","Anzahl an Bildern die bei Slider auf Startseite angezeigt werden", "SliderImageCount").'
                        '.SettingOption("S","Slider-Animation auf Startseite", "SliderAnimation", $sliderAnimations).'
                        '.SettingOption("N","Position des \"Spieler des Monats\"-Beitrags im Slider", "SDMSliderPosition").'
                    </table>
                </form>
            ';
        }
        else if($_GET['topic']=="Fusszeile")
        {
            echo '<h3>Sponsoren</h3>';

            if(isset($_GET['neu']))
            {
                echo '
                    <form action="'.ThisPage("-neu").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                            <h4>Neuen Sponsoren eintragen</h4>
                            <table>
                                <tr>
                                    <td class="ta_r">Name: </td>
                                    <td><input type="text" name="name" placeholder="Name..." required/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Website: </td>
                                    <td><input type="text" name="website" placeholder="http://..."/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Logo: </td>
                                    <td>'.FileButton("sponsorLogo","sponsorLogo").'</td>
                                </tr>
                                <tr>
                                    <td colspan=2 class="ta_c">
                                        <br><button type="submit" name="addSponsor">Sponsor hinzuf&uuml;gen</button>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </form>
                ';
            }

            echo '<ul>';
            $strSQL = "SELECT * FROM sponsors";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                if(isset($_GET['edit']) AND $_GET['edit']==$row['id'])
                {
                    echo '
                        <form action="'.ThisPage("!edit").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <table>
                                <tr>
                                    <td rowspan=2><li></li></td>
                                    <td><input type="text" name="name" value="'.$row['name'].'" placeholder="Name..."/></td>
                                    <td><input type="text" name="website" value="'.$row['link'].'" placeholder="http://..."/></td>
                                    <td>'.FileButton("sponsorLogo","sponsorLogo").'</td>
                                </tr>
                                <tr>
                                    <td><button type="submit" name="updateSponsor" value="'.$row['id'].'">Aktualisieren</button></td>
                                </tr>
                            </table>
                        </form>
                    ';
                }
                else
                {
                    echo '<li><a href="'.$row['link'].'">'.$row['name'].'</a>';
                    if(CheckPermission("ChangeContent")) echo EditButton(ThisPage("+edit=".$row['id']),true);
                    if(CheckPermission("ChangeContent")) echo DeleteButton("CC","sponsors",$row['id'],true,true);
                    echo '</li>';
                }

            }
            echo '</ul><br>';

            if(CheckPermission("ChangeContent")) echo AddButton(ThisPage("+neu"));
        }
        else if($_GET['topic'] == 'Dateien')
        {
            echo '<h3>Hochgeladene Dateien:</h3>';

            echo '<ul>';
            $strSQL = "SELECT * FROM uploads ORDER BY displayname ASC";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo '
                    <table style="margin-bottom: 5px;" class="hoverFocus">
                        <tr>
                            <td rowspan=2>
                                <li></li>
                            </td>
                            <td>
                                <span style="font-size: 12pt;">'.$row['displayname'].'</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color: #696969; font-size: 10pt;">['.$row['filename'].']</span>
                            </td>
                            <td>
                                '.DeleteButton("CC","uploads",$row['id'],false,true).'
                            </td>
                        </tr>
                    </table>
                ';
            }
            echo '</ul>';
        }
//========================================================================================
//========================================================================================
//  NUTZER
//========================================================================================
//========================================================================================
        else if($_GET['topic'] == 'Nutzer')
        {
            if(isset($_GET['cuser']))
            {
                $uid = $_GET['cuser'];
                $uDat = MySQL::Row("SELECT * FROM users WHERE id = ?",'s',$_GET['cuser']);

                echo '<br><h3>Vereins-Account Bearbeiten</h3>';

                echo '
                    <form action="'.ThisPage("!cuser").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                            <table>
                                <tr>
                                    <td class="ta_r">E-Mail: </td>
                                    <td colspan=2>
                                        <input type="email" placeholder="E-Mail" name="email" oninput="EMailCheck(this,\'outMailMessage\',\'submitButton\');" value="'.$uDat['email'].'" required/><br>
                                        <span style="color: red"><output id="outMailMessage"></output></span>
                                    </td>
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
                                        while($row=mysqli_fetch_assoc($rs)) echo '<option value="'.$row['kennzahl'].'" '.($row['kennzahl']==$uDat['club'] ? 'selected' : '').'>'.$row['kennzahl'].' - '.$row['verein'].' '.$row['ort'].'</option>';

                                        echo '
                                    </select><br>
                                    Verein nicht gefunden? <a href="/vereine/neu" target="_top">Hier neuen Verein anlegen</a>
                                    </td>
                                </tr>
                            </table>
                            <br>

                            Der Nutzer kann nach der ersten Anmeldung das Passwort &auml;ndern

                            <br><br>

                            <button type="submit" name="updateUserClubManager" value="'.$uDat['id'].'" id="submitButton">Aktualisieren</button>
                        </center>
                    </form>
                ';
            }
            else if(isset($_GET['user']))
            {
                $uid = $_GET['user'];
                $uDat = MySQL::Row("SELECT * FROM users WHERE id = ?",'s',$_GET['user']);

                echo '<br><h3>Nutzerdaten von <i>'.$uDat['firstname'].' '.$uDat['lastname'].'</i></h3>';



                if(isset($_GET['edit']))
                {
                    echo '
                        <form action="'.ThisPage("-edit").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <center>
                                <table>
                                    <tr>
                                        <td class="ta_r">Anrede:</td>
                                        <td>'.RadioButton("Herr","gender",$uDat['sex']=='M',"M").'</td>
                                        <td>'.RadioButton("Frau","gender",$uDat['sex']=='W',"W").'</td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Vorname: </td>
                                        <td colspan=2><input type="text" placeholder="Vorname" name="firstname" value="'.$uDat['firstname'].'"/></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">Nachname: </td>
                                        <td colspan=2><input type="text" placeholder="Nachname" name="lastname" value="'.$uDat['lastname'].'"/></td>
                                    </tr>
                                    <tr>
                                        <td class="ta_r">E-Mail: </td>
                                        <td colspan=2>
                                            <input type="email" placeholder="E-Mail" name="email" oninput="EMailCheck(this,\'outMailMessage\',\'submitButton\');" value="'.$uDat['email'].'" required/><br>
                                            <span style="color: red"><output id="outMailMessage"></output></span>
                                        </td>
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
                                                    <td style="width: 50px;">'.Checkbox("ChangeContent","ChangeContent",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ChangeContent' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Seiteninhalte verwalten / &auml;ndern</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("ManageSettings","ManageSettings",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManageSettings' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Seiteneinstellungen ver&auml;ndern</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 50%">
                                            <h5>Nutzer <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Nutzern und deren Rechten"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("ManageUsers","ManageUsers",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManageUsers' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Nutzer hinzuf&uuml;gen (registrieren) / entfernen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("ManagePermissions","ManagePermissions",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManagePermissions' AND user_id = ?",'@s',$uid)).'</td>
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
                                                    <td style="width: 50px;">'.Checkbox("AddNews","AddNews",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNews' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditNews","EditNews",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNews' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteNews","DeleteNews",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNews' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Termine <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Terminen im Kalender"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddDate","AddDate",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddDate' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditDate","EditDate",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditDate' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteDate","DeleteDate",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteDate' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Vereine <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Vereinen"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddClub","AddClub",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddClub' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditClub","EditClub",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditClub' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteClub","DeleteClub",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteClub' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Galerie <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Fotogalerien"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddGallery","AddGallery",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddGallery' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditGallery","EditGallery",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditGallery' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteGallery","DeleteGallery",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteGallery' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td style="width: 25%">
                                            <h5>Trainingsgruppen <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Trainingsgruppen in der Rubrik Nachwuchs"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddNWTG","AddNWTG",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNWTG' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditNWTG","EditNWTG",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNWTG' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteNWTG","DeleteNWTG",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNWTG' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Nachwuchskader <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Spielern im Nachwuchskader"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddNWK","AddNWK",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNWK' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditNWK","EditNWK",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNWK' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteNWK","DeleteNWK",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNWK' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Vorstand <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Mitgliedern im Vorstand"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddVorstand","AddVorstand",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddVorstand' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditVorstand","EditVorstand",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditVorstand' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteVorstand","DeleteVorstand",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteVorstand' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 25%">
                                            <h5>Zentralausschreibungen <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Spielern im Nachwuchskader"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddZA","AddZA",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddZA' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditZA","EditZA",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditZA' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteZA","DeleteZA",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteZA' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <table>
                                    <tr>
                                        <td style="width: 50%">
                                            <h5>Spielerranglisten <i class="fas fa-info-circle" title="Rechte f&uuml;r das verwalten von Spielerranglisten in der Rubrik O&Ouml;MM > Allgemein"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditSpielerrangliste","EditSpielerrangliste",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditSpielerrangliste' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td style="width: 50%">
                                            <h5>O&Ouml;BV-Ranglisten <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Ranglisten in der Rubrik Ranglisten > O&Ouml;BV"></i></h5>
                                            <table>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("AddOOEBVRLJgnd","AddOOEBVRLJgnd",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddOOEBVRLJgnd' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("EditOOEBVRLJgnd","EditOOEBVRLJgnd",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditOOEBVRLJgnd' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50px;">'.Checkbox("DeleteOOEBVRLJgnd","DeleteOOEBVRLJgnd",MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteOOEBVRLJgnd' AND user_id = ?",'@s',$uid)).'</td>
                                                    <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <br><br>
                                <button type="submit" name="updateUserAdministrative" id="submitButton" value="'.$uDat['id'].'">Nutzer aktualisieren</button>
                            </center>
                        </form>
                    ';
                }
                else
                {
                    if(CheckPermission("ManageUsers") OR $_SESSION['userID'] == $uDat['id']) echo EditButton(ThisPage("+edit"));
                    if(CheckPermission("ManageUsers")) echo DeleteButton("ManageUsers","users",$uDat['id'],false,true);

                    $dotGreen = '<i class="fas fa-circle" style="color: #32CD32"></i>';
                    $dotRed = '<i class="fas fa-circle" style="color: #FF0000"></i>';

                    echo '<br><br><h4>Verwaltung</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ChangeContent' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Seiteninhalte verwalten / &auml;ndern<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManageSettings' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Seiteneinstellungen ver&auml;ndern<br>';

                    echo '<br><h4>Nutzer</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManageUsers' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Nutzer hinzuf&uuml;gen (registrieren) / entfernen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'ManagePermissions' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Nutzerrechte verwalten<br>';

                    echo '<br><h4>News</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNews' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' News-Artikel erstellen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNews' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' News-Artikel bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNews' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' News-Artikel l&ouml;schen<br>';

                    echo '<br><h4>Termine</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddDate' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Termine erstellen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditDate' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Termine bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteDate' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Termine l&ouml;schen<br>';

                    echo '<br><h4>Vereine</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddClub' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vereine hinzuf&uuml;gen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditClub' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vereine bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteClub' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vereine l&ouml;schen<br>';

                    echo '<br><h4>Galerie</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddGallery' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Fotogalerie erstellen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditGallery' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Fotogalerie bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteGallery' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Fotogalerie l&ouml;schen<br>';

                    echo '<br><h4>Nachwuchskader</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNWK' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Nachwuchskader-Mitglied hinzuf&uuml;gen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNWK' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Nachwuchskader-Mitglied bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNWK' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Nachwuchskader-Mitglied l&ouml;schen<br>';

                    echo '<br><h4>Trainingsgruppen</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddNWTG' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Trainingsgruppen hinzuf&uuml;gen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditNWTG' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Trainingsgruppen bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteNWTG' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Trainingsgruppen l&ouml;schen<br>';

                    echo '<br><h4>Vorstand</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddVorstand' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vorstand-Mitglied hinzuf&uuml;gen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditVorstand' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vorstand-Mitglied bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteVorstand' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Vorstand-Mitglied l&ouml;schen<br>';

                    echo '<br><h4>Zentralausschreibungen</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddZA' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Zentralausschreibung erstellen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditZA' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Zentralausschreibung bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteZA' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Zentralausschreibung l&ouml;schen<br>';

                    echo '<br><h4>Spielerranglisten</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditSpielerrangliste' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' Spielerranglisten bearbeiten<br>';

                    echo '<br><h4>O&Ouml;BV-Ranglisten</h4>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'AddOOEBVRLJgnd' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' O&Ouml;BV-Ranglisten erstellen<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'EditOOEBVRLJgnd' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' O&Ouml;BV-Ranglisten bearbeiten<br>';
                    echo ((MySQL::Scalar("SELECT allowed FROM permissions WHERE permission = 'DeleteOOEBVRLJgnd' AND user_id = ?",'@s',$uid)) ? $dotGreen : $dotRed).' O&Ouml;BV-Ranglisten l&ouml;schen<br>';
                }
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
                                <td style="width: 25%">
                                    <h5>Trainingsgruppen <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Trainingsgruppen in der Rubrik Nachwuchs"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddNWTG","AddNWTG",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditNWTG","EditNWTG",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteNWTG","DeleteNWTG",true).'</td>
                                            <td><i class="fas fa-minus-square" style="color: #FF0000"></i> L&ouml;schen</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 25%">
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

                                <td style="width: 25%">
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

                                <td style="width: 25%">
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
                        <br>
                        <table>
                            <tr>
                                <td style="width: 50%">
                                    <h5>Spielerranglisten <i class="fas fa-info-circle" title="Rechte f&uuml;r das verwalten von Spielerranglisten in der Rubrik O&Ouml;MM > Allgemein"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditSpielerrangliste","EditSpielerrangliste",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="width: 50%">
                                    <h5>O&Ouml;BV-Ranglisten <i class="fas fa-info-circle" title="Rechte f&uuml;r das erstellen und verwalten von Ranglisten in der Rubrik Ranglisten > O&Ouml;BV"></i></h5>
                                    <table>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("AddOOEBVRLJgnd","AddOOEBVRLJgnd",true).'</td>
                                            <td><i class="fas fa-plus-square" style="color: #32CD32"></i> Erstellen</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("EditOOEBVRLJgnd","EditOOEBVRLJgnd",true).'</td>
                                            <td><i class="fas fa-pen-square" style="color: #1E90FF"></i> Bearbeiten</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50px;">'.Checkbox("DeleteOOEBVRLJgnd","DeleteOOEBVRLJgnd",true).'</td>
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

                $strSQL = "SELECT *, users.id AS uid FROM users INNER JOIN vereine ON users.club = vereine.kennzahl WHERE users.rank = 'clubmanager'";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '<li>'.$row['verein'].' '.$row['ort'].' <span style="color: #696969">['.$row['email'].']</span>';

                    if(CheckPermission("ManageUsers")) echo EditButton(ThisPage("+cuser=".$row['uid']),true);
                    if(CheckPermission("ManageUsers")) echo DeleteButton("ManageUsers","users",$row['uid'],true,true);

                    echo '</li>';
                }

                echo '</ul>';
            }
        }

    }

    echo '

                </div>
            </body>
        </html>
    ';

?>