<?php
    require("header.php");
    PageTitle("Vereine");


    if(isset($_POST['add_verein']) OR isset($_POST['edit_verein']))
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
        $isOOEclub = isset($_POST['isOOEclub']) ? 1 : 0;

        if(isset($_POST['add_verein']))
        {
            MySQL::NonQuery("INSERT INTO vereine (verein, ort, kennzahl, dachverband, website, contact_name, contact_street, contact_city, contact_email, contact_phoneLabel1, contact_phone1, contact_phoneLabel2, contact_phone2, contact_phoneLabel3, contact_phone3, isOOEclub) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",'@s',$verein,$ort,$kennzahl,$dachverband,$website,$name,$street,$city,$email,$label1,$phone1,$label2,$phone2,$label3,$phone3,$isOOEclub);
            FileUpload("content/clubs/","clubImg","","","UPDATE vereine SET clubImage = 'FNAME' WHERE kennzahl = '$kennzahl'",uniqid());
        }
        else
        {
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
            contact_phone3 = ?,
            isOOEclub = ?
            WHERE vereine.id = ?;";

            MySQL::NonQuery($strSQL,'@s',$verein,$ort,$kennzahl,$dachverband,$website,$name,$street,$city,$email,$label1,$phone1,$label2,$phone2,$label3,$phone3,$isOOEclub,$id);
            FileUpload("content/clubs/","clubImg","","","UPDATE vereine SET clubImage = 'FNAME' WHERE id = '$id'",uniqid());
        }




        Redirect("/vereine");
        die();
    }






    if((isset($_GET['neu']) AND CheckPermission("AddClub")) OR (isset($_GET['edit']) AND CheckPermission("EditClub")))
    {
        $edit = isset($_GET['edit']);

        echo '<h2 class="stagfade1">'.($edit ? 'Verein Bearbeiten' : 'Neuer Verein').'</h2>';

        if($edit) $cdat = MySQL::Row("SELECT * FROM vereine WHERE id = ?",'s',$_GET['edit']);

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr><td colspan=2><b>Verein:</b></td></tr>
                    <tr>
                        <td class="ta_r">Verein:</td>
                        <td><input type="text" name="verein" placeholder="z.B. Sportunion" value="'.($edit ? $cdat['verein'] : '').'" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Ort:</td>
                        <td><input type="text" name="ort" placeholder="z.B. Altm&uuml;nster" value="'.($edit ? $cdat['ort'] : '').'" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Kennzahl:</td>
                        <td><input type="number" name="kennzahl" placeholder="Kennzahl..." value="'.($edit ? $cdat['kennzahl'] : '').'" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Dachverband:</td>
                        <td><input type="text" name="dachverband" placeholder="Dachverband..." value="'.($edit ? $cdat['dachverband'] : '').'" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Website:</td>
                        <td><input type="url" name="website" placeholder="http://..." value="'.($edit ? $cdat['website'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Verein aus O&Ouml; :</td>
                        <td>
                            '.Tickbox("isOOEclub","isOOEclub",'<span title="Diese Option kann abgew&auml;hlt werden, wenn der Verein ausschlie&szlig;lich f&uuml;r Ranglisten etc. ben&ouml;tigt wird (Scheint nicht unter \'Vereine\' auf)"><i class="fas fa-info-circle"></i></span>',($edit ? ($cdat['isOOEclub']==1 ? true : false) : true )).'
                        </td>
                    </tr>
                    <tr><td colspan=2><b>Kontaktperson:</b></td></tr>
                    <tr>
                        <td class="ta_r">Name:</td>
                        <td><input type="text" name="name" placeholder="Vor- & Nachname" value="'.($edit ? $cdat['contact_name'] : '').'" required/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Stra&szlig;e:</td>
                        <td><input type="text" name="street" placeholder="Stra&szlig;e & Hausnummer" value="'.($edit ? $cdat['contact_street'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Stadt:</td>
                        <td><input type="text" name="city" placeholder="PLZ & Stadt" value="'.($edit ? $cdat['contact_city'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">E-Mail:</td>
                        <td><input type="email" name="email" placeholder="E-Mail..." value="'.($edit ? $cdat['contact_email'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">
                            <select name="label1">
                                <option value="">Kontaktart #1</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Mobil") ? 'selected' : ''). ' value="Mobil">Mobil</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                            </select>
                        </td>
                        <td><input type="text" name="phone1" placeholder="Telefonnummer..." value="'.($edit ? $cdat['contact_phone1'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">
                            <select name="label2">
                                <option value="">Kontaktart #2</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel2']=="Mobil") ? 'selected' : '').' value="Mobil">Mobil</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel2']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel2']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel2']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                            </select>
                        </td>
                        <td><input type="text" name="phone2" placeholder="Telefonnummer..." value="'.($edit ? $cdat['contact_phone2'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">
                            <select name="label3">
                                <option value="">Kontaktart #3</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel3']=="Mobil") ? 'selected' : '').' value="Mobil">Mobil</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel3']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel3']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel3']=="Fax") ? 'selected' : '').' value="Fax">Fax</option>
                            </select>
                        </td>
                        <td><input type="text" name="phone3" placeholder="Telefonnummer..." value="'.($edit ? $cdat['contact_phone3'] : '').'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Vereins-Logo:</td>
                        <td class="ta_r">'.FileButton("clubImg", "clubImg").'</td>
                    </tr>
                    <tr>
                        <td colspan=2 class="ta_c"><button type="submit"  '.($edit ? ('value="'.$cdat['id'].'" name="edit_verein"') : 'name="add_verein"').'>'.($edit ? '&Auml;nderungen speichern' : 'Hinzuf&uuml;gen').'</button></td>
                    </tr>
                </table>
            </form>
        ';
    }
    else
    {
        echo '<h1 class="stagfade1">Vereine</h1>';
        if(CheckPermission("AddClub")) echo AddButton("/vereine/neu").'<br>';

        echo '<br>Alphabetisch Sortiert:';
        $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine WHERE isOOEclub = '1'  ORDER BY ort ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs)) echo ' | <a href="#'.$row['letter'].'">'.$row['letter'].'</a>';
        echo ' |<br><br>';


        $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine WHERE isOOEclub = '1' ORDER BY ort ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $letter = $row['letter'];

            echo '<a name="'.$letter.'"></a>';

            echo '<center>';
            $strSQLo = "SELECT * FROM vereine WHERE ort LIKE '$letter%' AND isOOEclub = '1' ";
            $rso=mysqli_query($link,$strSQLo);
            while($rowo=mysqli_fetch_assoc($rso))
            {
                echo '
                    <table class="clubTables">
                        <tr>
                            <td style="width:300px;" rowspan=3><h4 style="margin: 4px;" ><a href="#alkhoven" name="alkhoven" id="'.$letter.'">'.$rowo['verein'].' '.$rowo['ort'].'</a></h4></td>
                            <td style="width:300px;"><b>'.$rowo['contact_name'].'</b></td>
                            <td style="width:180px;">'.(($rowo['contact_phone1']!='') ? ($rowo['contact_phoneLabel1'].': '.$rowo['contact_phone1'].'<br>') : '' ).'</td>
                            <td style="width:300px;" rowspan=5>'.($rowo['clubImage']!="" ? ('<img src="/content/clubs/'.$rowo['clubImage'].'" alt=""/>') : '').'</td>
                        </tr>
                        <tr>
                            <td>'.$rowo['contact_street'].'</td>
                            <td>'.(($rowo['contact_phone2']!='') ? ($rowo['contact_phoneLabel2'].': '.$rowo['contact_phone2'].'<br>') : '' ).'</td>
                        </tr>
                        <tr>
                            <td>'.$rowo['contact_city'].'</td>
                            <td>'.(($rowo['contact_phone3']!='') ? ($rowo['contact_phoneLabel3'].': '.$rowo['contact_phone3'].'<br>') : '' ).'</td>
                        </tr>
                        <tr>
                            <td>Kennzahl: '.$rowo['kennzahl'].'</td>
                            <td>E-mail: <a href="mailto:'.$rowo['contact_email'].'">'.$rowo['contact_email'].'</a></td>
                        </tr>
                        <tr>
                            <td>Dachverband: '.$rowo['dachverband'].'</td>
                            <td>'.(($rowo['website']!="") ? ('Website: <a href="'.$rowo['website'].'" target="_blank">'.str_replace('http://','',str_replace('https://','',$rowo['website'])).'</a><br>') : '').'</td>
                        </tr>
                        <tr>
                            <td colspan="5">
                            ';
                                if(CheckPermission("EditClub")) echo EditButton("/vereine?edit=".$rowo['id']);
                                if(CheckPermission("DeleteClub")) echo DeleteButton("Club","vereine",$rowo['id']);
                            echo '
                            </td>
                        </tr>
                    </table>
                    <br>
                ';
            }

            echo '</center>';
        }


        if(CheckPermission("EditClub") OR CheckPermission("DeleteClub") OR CheckPermission("AddClub"))
        {
            echo '<h4>Sonstige Vereine (nicht in O&Ouml; / Aushilfsspieler / etc.)</h4>';

            echo '
                Hier werden Vereine angezeigt, die ausschlie&szlig;lich zur f&uuml;llung von Ranglisten ben&ouml;tigt werden.<br>
                <br>
                Ein solcher Verein kann unter "Vereine > +Hinzuf&uuml;gen" erstellt werden, indem die Box "Verein aus O&Ouml;" beim erstellen abgew&auml;hlt wird.
                <br>
                Sie werden nicht in der obenstehenden Liste angezeigt.<br>
                Wird ein hier aufgelisteter Eintrag bereits in einer Rangliste verwendet, sollte der Verein nicht gel&ouml;scht werden, da<br>
                die Daten der betroffenen Rangliste daraufhin nicht mehr aktuell sein k&ouml;nten!<br>
                <br>
                Ist die Vereins-Kennzahl nicht bekannt, kann eine beliebige, noch nicht verwendete Kennzahl verwendet werden.<br>
                <br>
            ';

            echo '<ul>';

            $helperClubs = MySQL::Cluster("SELECT * FROM vereine WHERE isOOEclub = '0'");
            foreach($helperClubs as $hclub)
            {
                echo '<li>'.$hclub['kennzahl'].' - '.$hclub['verein'].' '.$hclub['ort'].' '.(CheckPermission("EditClub") ? EditButton("/vereine?edit=".$hclub['id'],true) : '').' '.(CheckPermission("DeleteClub") ? DeleteButton("Club","vereine",$hclub['id'],true): '').'</li>';
            }

            echo '</ul>';
        }
    }

    include("footer.php");
?>