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

        if(isset($_POST['add_verein'])) MySQLNonQuery("INSERT INTO vereine (id, verein, ort, kennzahl, dachverband, website, contact_name, contact_street, contact_city, contact_email, contact_phoneLabel1, contact_phone1, contact_phoneLabel2, contact_phone2, contact_phoneLabel3, contact_phone3) VALUES (NULL, '$verein', '$ort', '$kennzahl', '$dachverband', '$website', '$name', '$street', '$city', '$email', '$label1', '$phone1', '$label2', '$phone2', '$label3', '$phone3')");
        else
        {
            $id = $_POST['edit_verein'];

            $strSQL = "UPDATE vereine SET
            verein = '$verein',
            ort = '$ort',
            kennzahl = '$kennzahl',
            dachverband = '$dachverband',
            website = '$website',
            contact_name = '$name',
            contact_street = '$street',
            contact_city = '$city',
            contact_email = '$email',
            contact_phoneLabel1 = '$label1',
            contact_phone1 = '$phone1',
            contact_phoneLabel2 = '$label2',
            contact_phone2 = '$phone2',
            contact_phoneLabel3 = '$label3',
            contact_phone3 = '$phone3'
            WHERE vereine.id = '$id';";

            MySQLNonQuery($strSQL);
        }


        Redirect("/vereine");
        die();
    }






    if((isset($_GET['neu']) AND CheckPermission("AddClub")) OR (isset($_GET['edit']) AND CheckPermission("EditClub")))
    {
        $edit = isset($_GET['edit']);

        echo '<h2 class="stagfade1">'.($edit ? 'Verein Bearbeiten' : 'Neuer Verein').'</h2>';

        if($edit) $cdat = FetchArray("vereine","id",$_GET['edit']);

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
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Mobil") ? 'selected' : ''). 'value="Mobil">Mobil</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Telefon Privat") ? 'selected' : '').' value="Telefon Privat">Telefon Privat</option>
                                <option '.(($edit AND $cdat['contact_phoneLabel1']=="Telefon Firma") ? 'selected' : '').' value="Telefon Firma">Telefon Firma</option>
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
                            </select>
                        </td>
                        <td><input type="text" name="phone3" placeholder="Telefonnummer..." value="'.($edit ? $cdat['contact_phone3'] : '').'"/></td>
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
        $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine ORDER BY ort ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs)) echo ' | <a href="#'.$row['letter'].'">'.$row['letter'].'</a>';
        echo ' |<br><br>';


        $strSQL = "SELECT DISTINCT LEFT(ort , 1) AS letter FROM vereine ORDER BY ort ASC";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            $letter = $row['letter'];

            echo '<a name="'.$letter.'"></a>';

            $strSQLo = "SELECT * FROM vereine WHERE ort LIKE '$letter%'";
            $rso=mysqli_query($link,$strSQLo);
            while($rowo=mysqli_fetch_assoc($rso))
            {
                echo '
                    <div>
                        <h4 style="margin: 4px;">
                        <a href="#alkhoven" name="alkhoven">'.$rowo['verein'].' '.$rowo['ort'].'</a>
                        </h4>
                        Kennzahl: '.$rowo['kennzahl'].'
                        <br>
                        Dachverband: '.$rowo['dachverband'].'
                        <br>
                        <br>
                        <b>'.$rowo['contact_name'].'</b>
                        <br>
                        '.$rowo['contact_street'].'
                        <br>
                        '.$rowo['contact_city'].'
                        <br>
                        '.(($rowo['website']!="") ? ('Website: <a href="'.$rowo['website'].'" target="_blank">'.str_replace('http://','',str_replace('https://','',$rowo['website'])).'</a><br>') : '').'
                        E-mail: <a href="mailto:'.$rowo['contact_email'].'">'.$rowo['contact_email'].'</a>
                        <br>
                        '.(($rowo['contact_phone1']!='') ? ($rowo['contact_phoneLabel1'].' '.$rowo['contact_phone1'].'<br>') : '' ).'
                        '.(($rowo['contact_phone2']!='') ? ($rowo['contact_phoneLabel2'].' '.$rowo['contact_phone2'].'<br>') : '' ).'
                        '.(($rowo['contact_phone3']!='') ? ($rowo['contact_phoneLabel3'].' '.$rowo['contact_phone3'].'<br>') : '' ).'
                        ';

                        if(CheckPermission("EditClub")) echo EditButton("/vereine?edit=".$rowo['id']);
                        if(CheckPermission("DeleteClub")) echo DeleteButton("Club","vereine",$rowo['id']);

                        echo '
                    </div>
                    <br><br>
                ';
            }
        }
    }

    include("footer.php");
?>