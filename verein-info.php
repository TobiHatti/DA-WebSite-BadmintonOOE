<?php
    require("header.php");

    if(isset($_POST['addMember']))
    {
        $uid = uniqid();
        $gender=$_POST['gender'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $birthdate=$_POST['birthdate'];
        $number=$_POST['number'];
        $club = SQL::Fetch("users","club","id",$_SESSION['userID']);

        SQL::NonQuery("INSERT INTO members (id,club,number,gender,firstname,lastname,birthdate) VALUES (?,?,?,?,?,?,?)",'@s',$uid,$club,$number,$gender,$firstname,$lastname,$birthdate);

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

        SQL::NonQuery("UPDATE members SET firstname = ?, lastname = ?, birthdate = ?, number = ? WHERE id = ?",'@s',$firstname,$lastname,$birthdate,$number,$id);

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

        SQL::NonQuery($strSQL,'@s',$verein,$ort,$kennzahl,$dachverband,$website,$name,$street,$city,$email,$label1,$phone1,$label2,$phone2,$label3,$phone3,$id);

        Redirect("/verein-info");
        die();
    }

    if(CheckRank() == "clubmanager")
    {
        $club = SQL::Fetch("users","club","id",$_SESSION['userID']);
        if(isset($_GET['mitglieder']))
        {
            if(isset($_GET['neu']))
            {
                echo '
                    <h2 class="stagfade1">Neuen Spieler eintragen</h2>
                    <hr>

                    <br>
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>
                            <table>
                                <tr>
                                    <td class="ta_r">Geschlecht:</td>
                                    <td>'.RadioButton("M&auml;nnlich","gender",1,"M").'</td>
                                    <td>'.RadioButton("Weiblich","gender",0,"W").'</td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Vorname:</td>
                                    <td colspan=2><input type="text" name="firstname" class="cel_l" placeholder="Vorname..." required/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Nachname:</td>
                                    <td colspan=2><input type="text" name="lastname" class="cel_l" placeholder="Nachname..." required/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Geburtsdatum:</td>
                                    <td colspan=2><input type="date" name="birthdate" class="cel_l" required/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r">Mitgliedsnummer:</td>
                                    <td colspan=2><input type="number" name="number" class="cel_l" placeholder="Mitgl. Nr." required/></td>
                                </tr>
                                <tr>
                                    <td class="ta_r"><br>Foto:</td>
                                    <td colspan=2><br>'.FileButton("image", "image")  .'</td>
                                </tr>
                                <tr><td colspan=3><hr></td></tr>
                                <tr>
                                    <td colspan=3 style="text-align:center"><br><button type="submit" name="addMember">Spieler eintragen</button></td>
                                </tr>
                            </table>
                        </center>
                    </form>

                ';
            }
            else
            {
                echo '
                    <h2 class="stagfade1">Mitglieder</h2>

                ';

                $strSQL = "SELECT * FROM members WHERE club = '$club' ORDER BY birthdate DESC";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    if(isset($_GET['edit']) AND $_GET['edit']==$row['id'])
                    {
                        echo '
                            <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : 'red').'; height: 130px;">
                                <div class="member_image"></div>
                                <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                    <table>
                                        <tr>
                                            <td style="width: 100px;">Vorn.: </td>
                                            <td><input type="text" name="firstname" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['firstname'].'" placeholder="Vorname..."/></td>
                                            <td rowspan=5>
                                            <button type="submit" name="updateMembers" style="padding: 3px;" value="'.$row['id'].'"><i class="fa fa-floppy-o" style="font-size:24px"></i></button></td>
                                        </tr>
                                            <td>Nachn.: </td>
                                            <td><input type="text" name="lastname" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['lastname'].'" placeholder="Nachname..."/></td>
                                        </tr>
                                        <tr>
                                            <td>Geb.: </td>
                                            <td><input type="date" name="birthdate" style="width:160px; margin: 0; font-size: 10pt;" class="cel_h18" value="'.$row['birthdate'].'"/></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 70px;">Mg.Nr.: </td>
                                            <td><input type="number" name="number" style="width:160px; margin: 0;" class="cel_h18" value="'.$row['number'].'" placeholder="Mitgl. Nr..."/></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 70px;">Bild: </td>
                                            <td>'.FileButton("image", "image",false,"","","width: 120px;")  .'</td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        ';
                    }
                    else
                    {
                        echo '
                            <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : 'red').';">
                                <div class="member_image">
                                    <img src="'.(($row['img'] != "") ? ('/content/members/'.$row['img']) : '/content/user.png' ).'" alt="" />
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
                                        <td>'.$row['number'].'</td>
                                    </tr>
                                </table>

                                <div style="position: absolute; bottom: 0px; right: 0px;">
                                ';
                                    echo EditButton("/verein-info/mitglieder?edit=".$row['id'],true);
                                    echo DeleteButton("ClubManager","members",$row['id'],true);
                                echo '
                                </div>
                            </div>
                        ';
                    }


                }

                echo '<br><br><a href="/verein-info/mitglieder?neu">Neuen Spieler hinzufügen</a> ';
            }
        }
        else
        {


            $cdata = SQL::FetchArray("vereine","kennzahl",SQL::Fetch("users","club","id",$_SESSION['userID']));

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
                                        <option '.(($cdata['contact_phoneLabel1']=="Mobil") ? 'selected' : ''). 'value="Mobil">Mobil</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                        <option '.(($cdata['contact_phoneLabel1']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
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

            $strSQL = "SELECT * FROM members WHERE club = '$club' ORDER BY birthdate DESC";
                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    echo '
                        <div class="member_info" style="border-left: 5px groove '.(($row['gender']=='M') ? 'blue' : 'red').';">
                            <div class="member_image">
                                <img src="'.(($row['img'] != "") ? ('/content/members/'.$row['img']) : '/content/user.png' ).'" alt="" />
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
                                    <td>'.$row['number'].'</td>
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