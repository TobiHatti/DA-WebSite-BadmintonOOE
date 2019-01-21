<?php
    require("header.php");

    if(isset($_POST['addMember']))
    {
        // Unused
        $uid = uniqid();
        $gender=$_POST['gender'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $birthdate=$_POST['birthdate'];
        $number=$_POST['number'];
        $club = MySQL::Scalar("SELECT club FROM users WHERE id = ?",'i',$_SESSION['userID']);

        MySQL::NonQuery("INSERT INTO members (id,clubID,playerID,gender,firstname,lastname,birthdate) VALUES (?,?,?,?,?,?,?)",'@s',$uid,$club,$number,$gender,$firstname,$lastname,$birthdate);

        FileUpload("content/members/","image","","","UPDATE members SET img = 'FNAME' WHERE id = '$uid'",uniqid());

        Redirect("/verein-info/mitglieder");
        die();
    }

    if(isset($_POST['updateMembers']))
    {
        $id = $_POST['updateMembers'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $birthdate=$_POST['birthdate'];
        $number=$_POST['number'];
        $gender = $_POST['gender'];

        MySQL::NonQuery("UPDATE members SET firstname = ?, lastname = ?, birthdate = ?, playerID = ?, gender = ? WHERE id = ?",'@s',$firstname,$lastname,$birthdate,$number,$gender,$id);

        FileUpload("content/members/","image","","","UPDATE members SET img = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect("/verein-info/mitglieder");
        die();
    }

    if(isset($_POST['edit_verein']))
    {
        $verein = $_POST['verein'];
        $ort = $_POST['ort'];
        $kennzahl = $_POST['kennzahl'];
        $dachverband = $_POST['dachverband'];
        $website = $_POST['website'];
        $name = $_POST['name'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $email = $_POST['email'];
        $label1 = $_POST['label1'];
        $label2 = $_POST['label2'];
        $label3 = $_POST['label3'];
        $phone1 = $_POST['phone1'];
        $phone2 = $_POST['phone2'];
        $phone3 = $_POST['phone3'];

        $id = $_POST['edit_verein'];

        $strSQL = "UPDATE vereine SET
        verein = ?,
        ort = ?,
        kennzahl = ?,
        dachverband = ?,
        website = ?,
        contact_name = ?,
        contact_street = ?,
        contact_city = ?,
        contact_email = ?,
        contact_phoneLabel1 = ?,
        contact_phone1 = ?,
        contact_phoneLabel2 = ?,
        contact_phone2 = ?,
        contact_phoneLabel3 = ?,
        contact_phone3 = ?
        WHERE vereine.id = ?;";

        MySQL::NonQuery($strSQL,'@s',$verein,$ort,$kennzahl,$dachverband,$website,$name,$street,$city,$email,$label1,$phone1,$label2,$phone2,$label3,$phone3,$id);

        Redirect("/verein-info");
        die();
    }

    if(isset($_POST['mergePlayers']))
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gender = $_POST['gender'];
        $mobileNr = $_POST['mobileNr'];
        $email = $_POST['email'];
        $birthdate = $_POST['birthdate'];

        $oldMember = $_GET['memberID'];
        $newMember = $_GET['mergeID'];

        // Update new Member
        MySQL::NonQuery("UPDATE members SET firstname = ?, lastname = ?, gender = ?, mobileNr = ?, email = ?, birthdate = ? WHERE id = ?",'@s',$firstname,$lastname,$gender,$mobileNr,$email,$birthdate,$newMember);

        // Update member-tables
        MySQL::NonQuery("UPDATE members_nachwuchskader SET memberID = ? WHERE memberID = ?",'ss',$oldMember,$newMember);
        MySQL::NonQuery("UPDATE members_ooebvrl SET memberID = ? WHERE memberID = ?",'ss',$oldMember,$newMember);
        MySQL::NonQuery("UPDATE members_spielerranglisten SET memberID = ? WHERE memberID = ?",'ss',$oldMember,$newMember);
        MySQL::NonQuery("UPDATE members_trainingsgruppen SET memberID = ? WHERE memberID = ?",'ss',$oldMember,$newMember);

        // Delete old member
        MySQL::NonQuery("DELETE FROM members WHERE id = ?",'s',$oldMember);

        Redirect("/verein-info/mitglieder");
        die();
    }

    if(CheckRank() == "clubmanager" OR CheckRank() == "administrative")
    {
        $club = MySQL::Scalar("SELECT club FROM users WHERE id = ?",'i',$_SESSION['userID']);
        if(isset($_GET['mitglieder']))
        {
            if(isset($_GET['neu']))
            {
                echo '<h2 class="stagfade1">Neuen Spieler eintragen</h2>';

                echo ' <iframe src="/memberAddFrame?assignUser=club&club='.$club.'" frameborder="0" style="width: 100%; height: 450px;"></iframe>';
            }
            else
            {
                echo '
                    <h2 class="stagfade1">Mitglieder</h2>

                ';

                $strSQL = "SELECT * FROM members WHERE clubID = '$club' ORDER BY birthdate DESC";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo PlayerDisplayClubInfo($row);

                }

                echo '<br><br><a href="/verein-info/mitglieder?neu">Neuen Spieler hinzufügen</a><br><br>';


                // Check for Missing arguments

                $playersNoGender = MySQL::Count("SELECT * FROM members WHERE clubID = ? AND gender = ''",'s',$club);
                $playersNoNumber = MySQL::Count("SELECT * FROM members WHERE clubID = ? AND SUBSTRING(playerID,1,3) = 'TMP'",'s',$club);


                if($playersNoGender !=0 OR $playersNoNumber !=0)
                {
                    echo '<h3>Warnung: Es gibt Spieler mit fehlenden Informationen!</h3>';

                    if($playersNoNumber != 0)
                    {
                        echo '<h4>'.$playersNoNumber.' Spieler ohne Mitglieds-Nummer</h4>';
                        echo '
                            Tragen Sie so bald wie m&ouml;glich die Mitgliedsnummer dieses Spielers ein, um Doppelte eintr&auml;ge zu vermeiden!<br><br>
                            <b>Info:</b> Sollte bereits ein Spieler mit Mitgliedsnummer passend zu dem Spieler ohne Mitgliedsnummer eingetragen sein,<br>
                            k&ouml;nnen Sie diesen mit "Zusammenf&uuml;hren" (" <i class="fas fa-compress"></i> ") auf den Spieler mit Mitgliedsnummer &uuml;bertragen.<br>
                            <span style="color: #BD0000"><b>Das l&ouml;schen des Spielers ohne Mitgliedsnummer soll in diesem Fall vermieden werden!</b></span><br><br>
                        ';

                        $playerData = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND SUBSTRING(playerID,1,3) = 'TMP'",'s',$club);
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNN");

                        echo '<br><br>';
                    }

                    if($playersNoGender != 0)
                    {
                        echo '<h4>'.$playersNoGender.' Spieler mit fehlendem Geschlecht</h4>';
                        echo 'Ohne Angabe des Geschlechts scheint der Spieler u.a. nicht in der Auswahl zur Spielerreihung auf!<br>';

                        $playerData = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND gender = ''",'s',$club);
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNG");

                        echo '<br><br>';
                    }
                }
            }
        }
        else if(isset($_GET['merge']))
        {
            echo '<h2 class="stagfade1">Spieler zusammenf&uuml;hren</h2>';

            if(!isset($_GET['mergeID']))
            {
                if($club == "") $club = MySQL::Scalar("SELECT clubID FROM members WHERE id = ?",'s',$_GET['memberID']);

                $player1Data = MySQL::Row("SELECT * FROM members WHERE id = ?",'s',$_GET['memberID']);
                $playerList = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND SUBSTRING(playerID,1,3) != 'TMP'",'s',$club);

                echo '
                    <center>
                        <table>
                            <tr>
                                <td style="text-align: center;"><h3>Spieler 1:</h3></td>
                                <td style="text-align: center;"><h3>&#9654;&#9654;&#9654;</h3></td>
                                <td style="text-align: center;"><h3>Spieler 2:</h3></td>

                            </tr>
                            <tr>
                                <td>'.PlayerDisplayClubInfo($player1Data).'</td>
                                <td></td>
                                <td>
                                    <select class="cel_l" onchange="RedirectSelectBoxParam(this,\'/verein-info/mitglieder/zusammenfuehren/'.$_GET['memberID'].'/??\');">
                                        <option value="" >Spieler ausw&auml;hlen...</option>
                                        ';

                                        foreach($playerList AS $player) echo '<option value="'.$player['id'].'">'.$player['playerID'].' - '.$player['firstname'].' '.$player['lastname'].'</option>';

                                        echo '
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </center>
                ';
            }
            else
            {
                echo 'W&auml;hlen Sie aus welcher Wert f&uuml;r den Spieler beibehalten werden sollte:';

                $player1 = MySQL::Row("SELECT * FROM members WHERE id = ?",'s',$_GET['memberID']);
                $player2 = MySQL::Row("SELECT * FROM members WHERE id = ?",'s',$_GET['mergeID']);


                echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                            <table style="font-size: 13pt;">
                                <tr>
                                    <td></td>
                                    <td style="text-align: right;"><h3>Spieler 1:</h3></td>
                                    <td style="text-align: center;"><h3>&#9654;&#9654;&#9654;</h3></td>
                                    <td style="text-align: left;"><h3>Spieler 2:</h3></td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>Geschlecht:</b></td>
                                    <td style="text-align:right;">'.$player1['gender'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","gender",0,$player1['gender']).'</td>
                                                <td>'.RadioButton("&nbsp;","gender",1,$player2['gender']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['gender'].'</td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>Vorname:</b></td>
                                    <td style="text-align:right;">'.$player1['firstname'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","firstname",0,$player1['firstname']).'</td>
                                                <td>'.RadioButton("&nbsp;","firstname",1,$player2['firstname']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['firstname'].'</td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>Nachname:</b></td>
                                    <td style="text-align:right;">'.$player1['lastname'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","lastname",0,$player1['lastname']).'</td>
                                                <td>'.RadioButton("&nbsp;","lastname",1,$player2['lastname']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['lastname'].'</td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>Geburtsdatum:</b></td>
                                    <td style="text-align:right;">'.$player1['birthdate'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","birthdate",0,$player1['birthdate']).'</td>
                                                <td>'.RadioButton("&nbsp;","birthdate",1,$player2['birthdate']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['birthdate'].'</td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>Telefon:</b></td>
                                    <td style="text-align:right;">'.$player1['mobileNr'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","mobileNr",0,$player1['mobileNr']).'</td>
                                                <td>'.RadioButton("&nbsp;","mobileNr",1,$player2['mobileNr']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['mobileNr'].'</td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;"><b>E-Mail:</b></td>
                                    <td style="text-align:right;">'.$player1['email'].'</td>
                                    <td style="text-align:center;">
                                        <table>
                                            <tr>
                                                <td>'.RadioButton("&nbsp;","email",0,$player1['email']).'</td>
                                                <td>'.RadioButton("&nbsp;","email",1,$player2['email']).'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="text-align:left;">'.$player2['email'].'</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan=3 style="text-align: center"><button type="submit" name="mergePlayers">Spieler zusammenf&uuml;hren</button></td>
                                </tr>
                            </table>


                        </center>
                    </form>
                ';
            }
        }
        else
        {
            $kennzahl = MySQL::Scalar("SELECT club FROM users WHERE id = ?",'i',$_SESSION['userID']);
            $cdata = MySQL::Row("SELECT * FROM vereine WHERE kennzahl = ?",'s',$kennzahl);

            echo '
                <h2 class="stagfade1">'.$cdata['verein'].' '.$cdata['ort'].'</h2>

                <h3 class="stagfade2">Ihr Verein</h3>
                <hr>
            ';


            if(isset($_GET['edit']))
            {
                echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <table>
                            <tr><td colspan=2><b>Verein:</b></td></tr>
                            <tr>
                                <td class="ta_r">Verein:</td>
                                <td><input type="text" name="verein" placeholder="z.B. Sportunion" value="'.$cdata['verein'].'" required/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Ort:</td>
                                <td><input type="text" name="ort" placeholder="z.B. Altm&uuml;nster" value="'.$cdata['ort'].'" required/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Kennzahl:</td>
                                <td><input type="number" name="kennzahl" placeholder="Kennzahl..." value="'.$cdata['kennzahl'].'" required/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Dachverband:</td>
                                <td><input type="text" name="dachverband" placeholder="Dachverband..." value="'.$cdata['dachverband'].'" required/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Website:</td>
                                <td><input type="url" name="website" placeholder="http://..." value="'.$cdata['website'].'"/></td>
                            </tr>
                            <tr><td colspan=2><b>Kontaktperson:</b></td></tr>
                            <tr>
                                <td class="ta_r">Name:</td>
                                <td><input type="text" name="name" placeholder="Vor- & Nachname" value="'.$cdata['contact_name'].'" required/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Stra&szlig;e:</td>
                                <td><input type="text" name="street" placeholder="Stra&szlig;e & Hausnummer" value="'.$cdata['contact_street'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Stadt:</td>
                                <td><input type="text" name="city" placeholder="PLZ & Stadt" value="'.$cdata['contact_city'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">E-Mail:</td>
                                <td><input type="email" name="email" placeholder="E-Mail..." value="'.$cdata['contact_email'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">
                                    <select name="label1">
                                        <option value="">Kontaktart #1</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Mobil") ? 'selected' : ''). ' value="Mobil">Mobil</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                                    </select>
                                </td>
                                <td><input type="text" name="phone1" placeholder="Telefonnummer..." value="'.$cdata['contact_phone1'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">
                                    <select name="label2">
                                        <option value="">Kontaktart #2</option>
                                        <option '.(($cdata['contact_phoneLabel2']=="Mobil") ? 'selected' : '').' value="Mobil">Mobil</option>
                                        <option '.(($cdata['contact_phoneLabel2']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                        <option '.(($cdata['contact_phoneLabel2']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                        <option '.(($cdata['contact_phoneLabel2']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                                    </select>
                                </td>
                                <td><input type="text" name="phone2" placeholder="Telefonnummer..." value="'.$cdata['contact_phone2'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">
                                    <select name="label3">
                                        <option value="">Kontaktart #3</option>
                                        <option '.(($cdata['contact_phoneLabel3']=="Mobil") ? 'selected' : '').' value="Mobil">Mobil</option>
                                        <option '.(($cdata['contact_phoneLabel3']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                        <option '.(($cdata['contact_phoneLabel3']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                        <option '.(($cdata['contact_phoneLabel3']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                                    </select>
                                </td>
                                <td><input type="text" name="phone3" placeholder="Telefonnummer..." value="'.$cdata['contact_phone3'].'"/></td>
                            </tr>
                            <tr>
                                <td colspan=2 class="ta_c"><button type="submit" value="'.$cdata['id'].'" name="edit_verein">&Auml;nderungen speichern</button></td>
                            </tr>
                        </table>
                    </form>
                ';
            }
            else
            {
                echo '
                    <div>
                        <h4 style="margin: 4px;">'.$cdata['verein'].' '.$cdata['ort'].'</h4>
                        Kennzahl: '.$cdata['kennzahl'].'
                        <br>
                        Dachverband: '.$cdata['dachverband'].'
                        <br>
                        <br>
                        <b>'.$cdata['contact_name'].'</b>
                        <br>
                        '.$cdata['contact_street'].'
                        <br>
                        '.$cdata['contact_city'].'
                        <br>
                        '.(($cdata['website']!="") ? ('Website: <a href="'.$cdata['website'].'" target="_blank">'.str_replace('http://','',str_replace('https://','',$cdata['website'])).'</a><br>') : '').'
                        E-mail: <a href="mailto:'.$cdata['contact_email'].'">'.$cdata['contact_email'].'</a>
                        <br>
                        '.(($cdata['contact_phone1']!='') ? ($cdata['contact_phoneLabel1'].' '.$cdata['contact_phone1'].'<br>') : '' ).'
                        '.(($cdata['contact_phone2']!='') ? ($cdata['contact_phoneLabel2'].' '.$cdata['contact_phone2'].'<br>') : '' ).'
                        '.(($cdata['contact_phone3']!='') ? ($cdata['contact_phoneLabel3'].' '.$cdata['contact_phone3'].'<br>') : '' ).'
                        ';

                        echo EditButton("/verein-info?edit");
                        echo '
                    </div>
                    <br><br>
                ';
            }






            echo '
                <h3 class="stagfade3">Spieler</h3>
                <hr>
            ';

            $strSQL = "SELECT * FROM members WHERE clubID = '$club' ORDER BY birthdate DESC";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '
                        <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : (($row['gender']=='F') ? 'red' : 'black')).';">
                            <div class="member_image">
                                <img src="'.(($row['image'] != "") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                            </div>
                            <table>
                                <tr>
                                    <td>Vorname: </td>
                                    <td>'.$row['firstname'].'</td>
                                </tr>
                                <tr>
                                    <td>Nachname: </td>
                                    <td>'.$row['lastname'].'</td>
                                </tr>
                                <tr>
                                    <td>Geb. Datum: </td>
                                    <td>'.str_replace('ä','&auml;',strftime("%d. %b. %Y",strtotime($row['birthdate']))).' ('.Age($row['birthdate']).')</td>
                                </tr>
                                <tr>
                                    <td>Mitgl. Nr.: </td>
                                    <td>'.(StartsWith($row['playerID'],"TMP") ? '<i>keine Mg.Nr.!</i>' : $row['playerID']).'</td>
                                </tr>
                            </table>
                        </div>
                    ';
                }

            echo '

            ';

        }

    }

    include("footer.php");
?>