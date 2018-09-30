<?php
    require("header.php");

//=================================================================================
//=================================================================================
//      POST - SECTION
//=================================================================================
//=================================================================================

if(isset($_POST['add_vorstand_member']))
{
    $name = $_POST['name'];
    $fields = $_POST['fields'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];

    MySQLNonQuery("INSERT INTO vorstand (id,darstellung,name,bereich,email,telefon) VALUES ('','$type','$name','$fields','$email','$phone')");

    FileUpload("content/vorstand/","image","","","UPDATE vorstand SET foto = 'FNAME' WHERE name = '$name'");

    Redirect(ThisPage());
}

if(isset($_POST['add_tag']))
{
    $tag = $_POST['tag'];

    $tagid = SReplace($tag);

    MySQLNonQuery("INSERT INTO news_tags (id,name) VALUES ('$tagid','$tag')");

    Redirect(ThisPage());
}

if(isset($_POST['add_nwk']))
{
    $fn = $_POST['firstname'];
    $ln = $_POST['lastname'];
    $gender = $_POST['gender'];
    $birthyear = $_POST['birthyear'];
    $club = $_POST['club'];

    MySQLNonQuery("INSERT INTO nachwuchskader (id,firstname,lastname,gender,club,birthyear) VALUES ('','$fn','$ln','$gender','$club','$birthyear')");

    Redirect(ThisPage());
}

if(isset($_POST['add_verein']))
{
    $verband = $_POST['verein'];
    $kennzahl = $_POST['kennzahl'];
    $dachverband = $_POST['dachverband'];
    $website = 'http://'.$_POST['website'];
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

    MySQLNonQuery("INSERT INTO vereine (id, name, kennzahl, dachverband, website, contact_name, contact_street, contact_city, contact_email, contact_phoneLabel1, contact_phone1, contact_phoneLabel2, contact_phone2, contact_phoneLabel3, contact_phone3) VALUES (NULL, '$verband', '$kennzahl', '$dachverband', '$website', '$name', '$street', '$city', '$email', '$label1', '$phone1', '$label2', '$phone2', '$label3', '$phone3')");

    Redirect(ThisPage());
}

if(isset($_POST['add_archive_entry']))
{
    $title = $_POST['title'];
    $year= $_POST['year'];

    for($i=1;$i <= 7 ; $i++)
    {
        if($_POST['Pl'.$i] == "") break;

        $Pl = $_POST['Pl'.$i];
        $Verein = $_POST['Verein'.$i];
        $Rd = $_POST['Rd'.$i];
        $S = $_POST['S'.$i];
        $U = $_POST['U'.$i];
        $N = $_POST['N'.$i];
        $Spiele = $_POST['Spiele'.$i];
        $Satze = $_POST['Satze'.$i];
        $Pkt = $_POST['Pkt'.$i];

        MySQLNonQuery("INSERT INTO ooemm_archive (id,year,table_name,table_row,c_pl,c_verein,c_rd,c_s,c_u,c_n,c_spiele,c_satze,c_pkt) VALUES ('','$year','$title','$i','$Pl','$Verein','$Rd','$S','$U','$N','$Spiele','$Satze','$Pkt')");
    }


}

if(isset($_POST['updateZA']))
{
    $kategorie = $_POST['kategorie'];

    $title1 = $_POST['title1'];
    $title2 = $_POST['title2'];

    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $chTimespan = (isset($_POST['ch_timespan']) ? 1 : 0 );

    $chVerein = (isset($_POST['ch_verein']) ? 1 : 0 );
    $chUhrzeit = (isset($_POST['ch_uhrzeit']) ? 1 : 0 );
    $chAuslosung = (isset($_POST['ch_auslosung']) ? 1 : 0 );
    $chHallenname = (isset($_POST['ch_hallenname']) ? 1 : 0 );
    $chAnschriftHalle = (isset($_POST['ch_anschrift_halle']) ? 1 : 0 );
    $chAnzahlFelder = (isset($_POST['ch_anzahl_felder']) ? 1 : 0 );
    $chTurnierverantwortlicher = (isset($_POST['ch_turnierverantwortlicher']) ? 1 : 0 );
    $chOberschiedsrichter = (isset($_POST['ch_oberschiedsrichter']) ? 1 : 0 );
    $chTelefon = (isset($_POST['ch_telefon']) ? 1 : 0 );
    $chAnmeldungOnline = (isset($_POST['ch_anmeldung_online']) ? 1 : 0 );
    $chAnmeldungEmail = (isset($_POST['ch_anmeldung_email']) ? 1 : 0 );
    $chNennungenEmail = (isset($_POST['ch_nennungen_email']) ? 1 : 0 );
    $chNennschluss = (isset($_POST['ch_nennschluss']) ? 1 : 0 );
    $chZusatzangaben = (isset($_POST['ch_zusatzangaben']) ? 1 : 0 );

    $Verein = $_POST['verein'];
    $Uhrzeit = $_POST['uhrzeit'];
    $Auslosung = $_POST['auslosung'];
    $Hallenname = $_POST['hallenname'];
    $AnschriftHalle = $_POST['anschrift_halle'];
    $AnzahlFelder = $_POST['anzahl_felder'];
    $Turnierverantwortlicher = $_POST['turnierverantwortlicher'];
    $Oberschiedsrichter = $_POST['oberschiedsrichter'];
    $Telefon = $_POST['telefon'];
    $AnmeldungOnline = $_POST['anmeldung_online'];
    $AnmeldungEmail = $_POST['anmeldung_email'];
    $NennungenEmail = $_POST['nennungen_email'];
    $Nennschluss = $_POST['nennschluss'];
    $Zusatzangaben = $_POST['zusatzangaben'];

    if($_POST['postType']=="new")
    {
        MySQLNonQuery("INSERT INTO zentralausschreibungen (id,kategorie) VALUES ('','newfield')");
        $zaID = Fetch("zentralausschreibungen","id","kategorie","newfield");
    }
    else $zaID = $_POST['updateZA'];

    $updateSQL = "
        UPDATE zentralausschreibungen SET
        kategorie = '$kategorie',
        title_line1 = '$title1',
        title_line2 = '$title2',
        date_begin = '$date1',
        date_end = '$date2',
        act_timespan = '$chTimespan',
        act_verein = '$chVerein',
        verein = '$Verein',
        act_uhrzeit = '$chUhrzeit',
        uhrzeit = '$Uhrzeit',
        act_auslosung = '$chAuslosung',
        auslosung = '$Auslosung',
        act_hallenname = '$chHallenname',
        hallenname = '$Hallenname',
        act_anschrift_halle = '$chAnschriftHalle',
        anschrift_halle = '$AnschriftHalle',
        act_anzahl_felder = '$chAnzahlFelder',
        anzahl_felder = '$AnzahlFelder',
        act_turnierverantwortlicher = '$chTurnierverantwortlicher',
        turnierverantwortlicher = '$Turnierverantwortlicher',
        act_oberschiedsrichter = '$chOberschiedsrichter',
        oberschiedsrichter = '$Oberschiedsrichter',
        act_telefon = '$chTelefon',
        telefon = '$Telefon',
        act_anmeldung_online = '$chAnmeldungOnline',
        anmeldung_online = '$AnmeldungOnline',
        act_anmeldung_email = '$chAnmeldungEmail',
        anmeldung_email = '$AnmeldungEmail',
        act_nennungen_email = '$chNennungenEmail',
        nennungen_email = '$NennungenEmail',
        act_nennschluss = '$chNennschluss',
        nennschluss = '$Nennschluss',
        act_zusatzangaben = '$chZusatzangaben',
        zusatzangaben = '$Zusatzangaben'
        WHERE id = '$zaID';
    ";
    MySQLNonQuery($updateSQL);

    Redirect(ThisPage());
    die();
}


//=================================================================================
//=================================================================================
//      PAGE - SECTION
//=================================================================================
//=================================================================================

    echo '
        <a href="__tempform"><h1 class="stagfade1">Temporary Form-File</h1></a>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    ';


    if(isset($_GET['vorstand']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: Vorand</h3> ';

        echo '
            <table>
                <tr>
                    <td>Darstellung</td>
                    <td>
                        <select name="type">
                            <option value="list">Liste</option>
                            <option value="box">Box</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="text" name="name"/></td>
                </tr>
                <tr>
                    <td>Gebiete</td>
                    <td><textarea name="fields">Schiedsgericht</textarea></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td><input type="email" name="email"/></td>
                </tr>
                <tr>
                    <td>Telefon</td>
                    <td><input type="tel" name="phone"/></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>'.FileButton('image','image').'</td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_vorstand_member">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['tags']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: News-Tags</h3> ';

        echo '
            <table>
                <tr>
                    <td>Tag-Name</td>
                    <td><input type="text" name="tag"/></td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_tag">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['nwk']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: Nachwuchskader</h3> ';

        echo '
            <table>
                <tr>
                    <td>Geschlecht</td>
                    <td>
                        <input type="radio" name="gender" value="M"> M&auml;nnlich
                        <input type="radio" name="gender" value="W"> Weiblich
                    </td>
                </tr>
                <tr>
                    <td>Vorname</td>
                    <td><input type="text" name="firstname"/></td>
                </tr>
                <tr>
                    <td>Nachname</td>
                    <td><input type="text" name="lastname"/></td>
                </tr>
                <tr>
                    <td>Geburtsjahr</td>
                    <td><input type="number" name="birthyear"/></td>
                </tr>
                <tr>
                    <td>Verein</td>
                    <td><input type="text" name="club"/></td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_nwk">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['ooemm-archiv']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: OOEMM-Archive</h3> ';

        echo '

        <div class="archiveTables">
            <select name="year">
                <option value="2005/2006">2005/2006</option>
                <option value="2004/2005">2004/2005</option>
                <option value="2003/2004">2003/2004</option>
                <option value="2002/2003">2002/2003</option>
                <option value="2001/2002">2001/2002</option>
                <option value="2000/2001">2000/2001</option>
                <option value="1999/2000">1999/2000</option>
            </select>

            <table>
                <tr>
                    <th colspan=9><input placeholder="Titel" name="title"/></td>
                </tr>
                <tr>
                    <td>Pl</td>
                    <td>Verein</td>
                    <td>Rd</td>
                    <td>S</td>
                    <td>U</td>
                    <td>N</td>
                    <td>Spiele</td>
                    <td>S&auml;tze</td>
                    <td>Pkt</td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl1" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein1" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd1" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S1" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U1" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N1" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele1" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze1" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt1" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl2" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein2" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd2" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S2" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U2" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N2" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele2" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze2" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt2" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl3" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein3" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd3" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S3" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U3" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N3" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele3" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze3" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt3" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl4" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein4" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd4" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S4" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U4" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N4" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele4" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze4" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt4" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl5" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein5" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd5" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S5" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U5" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N5" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele5" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze5" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt5" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl6" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein6" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd6" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S6" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U6" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N6" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele6" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze6" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt6" placeholder="Pkt"/></td>
                </tr>

                <tr>
                    <td><input class="cel_xxs" name="Pl7" placeholder="Pl"/></td>
                    <td><input class="cel_s" name="Verein7" placeholder="Verein"/></td>
                    <td><input class="cel_xxs" name="Rd7" placeholder="Rd"/></td>
                    <td><input class="cel_xxs" name="S7" placeholder="S"/></td>
                    <td><input class="cel_xxs" name="U7" placeholder="U"/></td>
                    <td><input class="cel_xxs" name="N7" placeholder="N"/></td>
                    <td><input class="cel_xs" name="Spiele7" placeholder="Spiele"/></td>
                    <td><input class="cel_xs" name="Satze7" placeholder="S&auml;tze"/></td>
                    <td><input class="cel_xxs" name="Pkt7" placeholder="Pkt"/></td>
                </tr>


            </table>
        </div>

        <button type="submit" name="add_archive_entry">Hinzuf&uuml;gen</button>
        ';
    }
    else if(isset($_GET['vereine']))
    {
        echo '<h3 class="stagfade2">[SQL-INSERT]: Vereine</h3> ';

        echo '
            <table>
                <tr>
                    <td>Verein:</td>
                    <td><input type="text" name="verein"/></td>
                </tr>
                <tr>
                    <td>Kennzahl:</td>
                    <td><input type="number" name="kennzahl"/></td>
                </tr>
                <tr>
                    <td>Dachverband:</td>
                    <td><input type="text" name="dachverband"/></td>
                </tr>
                <tr>
                    <td>Website:</td>
                    <td><input type="text" name="website"/></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name"/></td>
                </tr>
                <tr>
                    <td>Stra&szlig;e:</td>
                    <td><input type="text" name="street"/></td>
                </tr>
                <tr>
                    <td>Stadt:</td>
                    <td><input type="text" name="city"/></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email"/></td>
                </tr>
                <tr>
                    <td>
                        <select name="label1">
                            <option value="Mobil">Mobil</option>
                            <option value="Telefon Privat">Telefon Privat</option>
                            <option value="Telefon Firma">Telefon Firma</option>
                        </select>
                    </td>
                    <td><input type="text" name="phone1"/></td>
                </tr>
                <tr>
                    <td>
                        <select name="label2">
                            <option value="Mobil">Mobil</option>
                            <option value="Telefon Privat">Telefon Privat</option>
                            <option value="Telefon Firma">Telefon Firma</option>
                        </select>
                    </td>
                    <td><input type="text" name="phone2"/></td>
                </tr>
                <tr>
                    <td>
                        <select name="label3">
                            <option value="Mobil">Mobil</option>
                            <option value="Telefon Privat">Telefon Privat</option>
                            <option value="Telefon Firma">Telefon Firma</option>
                        </select>
                    </td>
                    <td><input type="text" name="phone3"/></td>
                </tr>
                <tr>
                    <td colspan=2><button type="submit" name="add_verein">Hinzuf&uuml;gen</button></td>
                </tr>
            </table>
        ';
    }
    else if(isset($_GET['za']))
    {
        $i=1;
        $j=8;
        echo '<h3 class="stagfade2">[SQL-INSERT]: Zentralausschreibungen</h3> ';

        echo '
            <br><br>

            <h3>Zentralausschreibung erstellen</h3>
            <br>

            <script>
                window.onload = function() {
                  CopyZADate();
                };
            </script>


            <table style="display:inline-table">
                <tr>
                    <td>Kategorie:</td>
                    <td colspan=2>
                        <select name="kategorie" id="zaKategory" onchange="SelectZAKategory();" class="cel_m" tabindex="1">
                            <option value="Landesmeisterschaft" style="color: #FF0000">Landesmeisterschaft</option>
                            <option value="Doppelturnier" style="color: #20B2AA">Doppelturnier</option>
                            <option value="Nachwuchs" style="color: #FFA500">Nachwuchs</option>
                            <option value="SchuelerJugend" style="color: #9400D3">Sch&uuml;ler/Jugend</option>
                            <option value="Senioren" style="color: #32CD32">Senioren</option>
                        </select>
                    </td>
                    <td>Beschreibung</td>
                    <td>
                        <input name="title1" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 1" class="cel_m" id="zaTitleLineIn1" value="" tabindex="4">
                    </td>
                </tr>
                <tr>
                    <td><b>Datum: </b><output id="outTimespan"></output></td>
                    <td colspan=2><input name="date1" type="date" onchange="CopyZADate();" id="datePick1" class="cel_m" value="'.date("Y-m-d").'" tabindex="2"/></td>
                    <td></td>
                    <td><input name="title2" type="text" oninput="CopyZATitle();" placeholder="Titel Zeile 2 (optional)" class="cel_m" id="zaTitleLineIn2" value="" tabindex="5"></td>
                </tr>
                <tr id="rwTimespan" style="display: none">
                    <td class="ta_r">Bis: </td>
                    <td colspan=2><input name="date2" type="date" onchange = "CopyZADate();" id="datePick2" class="cel_m" value="'.(date("Y-m-d",strtotime(date("Y-m-d")."+1 days"))).'" tabindex="3"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td>'.Checkbox("ch_timespan","chTimespan",0,"CopyZADate();").'</td>
                    <td>Zeitpsanne</td>
                </tr>
            </table>


            <table style="display:inline-table">
                <tr>
                    <td>'.Checkbox("ch_verein","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Verein</td>
                    <td>'.Checkbox("ch_oberschiedsrichter","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Oberschiedsrichter</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_uhrzeit","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Uhrzeit</td>
                    <td>'.Checkbox("ch_telefon","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Telefon</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_auslosung","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Auslosung</td>
                    <td>'.Checkbox("ch_anmeldung_online","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Anmeldung Online</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_hallenname","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Hallenname</td>
                    <td>'.Checkbox("ch_anmeldung_email","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Anmeldung E-Mail</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_anschrift_halle","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Anschrift Halle</td>
                    <td>'.Checkbox("ch_nennungen_email","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Nennungen E-Mail</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_anzahl_felder","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Anzahl Felder</td>
                    <td>'.Checkbox("ch_nennschluss","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Nennschluss</td>
                </tr>
                <tr>
                    <td>'.Checkbox("ch_turnierverantwortlicher","chid".$i,1,"ChangeZAExtraData(".$i++.");").'</td>
                    <td>Turnierverantwortlicher</td>
                    <td>'.Checkbox("ch_zusatzangaben","chid".$j,1,"ChangeZAExtraData(".$j++.");").'</td>
                    <td>Zusatzangaben</td>
                </tr>
            </table>

            ';

            $i=1;

            echo '


            <div class="za_box">
                <div class="za_title">

                    <h1>
                        <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut1">Titel/Beschreibung</output>
                        <br>
                        <output style="color: #FF0000" class="cel_l cef_nobg cef_brdb" id="zaTitleLineOut2"></output>
                    </h1>
                    <output class="cel_f15" id="zaDate"></output>

                </div>
                <div class="za_data">
                    <table>
                        <tr id="edat'.$i.'">
                            <td class="ta_r"><b>Verein:</b></td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <b><input name="verein" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd" id="verein_in"/></b>
                                <select id="vereinSelection" onchange="UpdateZAVerein();" style="width: 40px;" class="cef_nomg cef_nopd">
                                    <option value="" disabled selected>&#9660; Verein&nbsp;&nbsp;ausw&auml;hlen</option>
                                    ';
                                    $strSQL = "SELECT * FROM vereine";
                                    $rs=mysqli_query($link,$strSQL);
                                    while($row=mysqli_fetch_assoc($rs))
                                    {
                                        echo '<option value="'.$row['verein'].' '.$row['ort'].'">'.$row['verein'].' <b>'.$row['ort'].'</b></option>';
                                    }
                                    echo '
                                </select>
                            </td>
                        </tr>
                        <tr id="edat'.$i.'">
                            <td class="ta_r">Uhrzeit:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="uhrzeit" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Auslosung:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="auslosung" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Hallenname:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="hallenname" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Anschrift Halle:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="anschrift_halle" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Anzahl Felder:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="anzahl_felder" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Turnierverantwortlicher:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="turnierverantwortlicher" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Oberschiedsrichter:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="oberschiedsrichter" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Telefon:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="telefon" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Anmeldung Online:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="anmeldung_online" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Anmeldung E-Mail:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="anmeldung_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Nennungen E-Mail:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="nennungen_email" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Nennschluss:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="nennschluss" type="date" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>

                        <tr id="edat'.$i.'">
                            <td class="ta_r">Zusatzangaben:</td>
                            <td class="ta_l">
                                <a onclick="DisableZAOption('.$i++.');" style="color: #696969; text-decoration: none;" title="Feld entfernen">&#128473;</a>
                                <input name="zusatzangaben" type="text" class="cel_m cef_nobg cef_brdb cef_nomg cef_nopd"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <input type="hidden" name="postType" value="new"/>
            <button type="submit" name="updateZA">Aktualisieren</button>
        ';
    }
    else
    {
        echo '<h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3> ';

        echo '
            <ul>
                <li><a href="?vorstand">[SQL-INSERT]: Vorand</a></li>
                <li><a href="?tags">[SQL-INSERT]: News-Tags</a></li>
                <li><a href="?ooemm-archiv">[SQL-INSERT]: OOEMM-Archiv</a></li>
                <li><a href="?vereine">[SQL-INSERT]: Vereine</a></li>
                <li><a href="?za">[SQL-INSERT]: Zentralausschreibungen</a></li>
                <li><a href="?nwk">[SQL-INSERT]: Nachwuchskader</a></li>
            </ul>
        ';
    }

    echo '</form>';

    require("footer.php");
?>