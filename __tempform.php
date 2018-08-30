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

    Redirect(ThisPage());
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
    else
    {
        echo '<h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3> ';

        echo '
            <ul>
                <li><a href="?vorstand">[SQL-INSERT]: Vorand</a></li>
                <li><a href="?tags">[SQL-INSERT]: News-Tags</a></li>
                <li><a href="?ooemm-archiv">[SQL-INSERT]: OOEMM-Archiv</a></li>
                <li><a href="?vereine">[SQL-INSERT]: Vereine</a></li>
            </ul>
        ';
    }

    echo '</form>';

    require("footer.php");
?>