<?php
    require("header.php");
    PageTitle("O\u00d6 Nachwuchskader");

    if(isset($_POST["add_nwk"]) OR isset($_POST["edit_nwk"]))
    {
        $id = uniqid();
        $fn = $_POST['firstname'];
        $ln = $_POST['lastname'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['birthdate'];
        $club = $_POST['club'];

        if(isset($_POST['add_nwk'])) MySQLNonQuery("INSERT INTO nachwuchskader (id,firstname,lastname,gender,club,birthdate) VALUES ('$id','$fn','$ln','$gender','$club','$birthdate')");
        else
        {
            $id = $_POST["edit_nwk"];

            $strSQL = "UPDATE nachwuchskader SET
            gender = '$gender',
            firstname = '$fn',
            lastname = '$ln',
            birthdate = '$birthdate',
            club = '$club'
            WHERE id = '$id'";

            MySQLNonQuery($strSQL);
        }

        FileUpload("/content/nachwuchskader/","playerImg","","","UPDATE nachwuchskader SET image = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect("/nachwuchskader");
        die();
    }

    if(isset($_GET["neu"]))
    {
        echo '
            <h2 class="stagfade1">O&Ouml; Nachwuchskader - Spieler hinzuf&uuml;gen</h2>
            <br>

            <form action="'.ThisPage("!editSC").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td class="ta_r">Geschlecht</td>
                        <td>
                            '.RadioButton("M&auml;nnlich", "gender",true,"M").'
                            '.RadioButton("Weiblich", "gender",false,"W").'
                        </td>
                    </tr>
                    <tr>
                        <td class="ta_r">Vorname: </td>
                        <td><input type="text" name="firstname" placeholder="Vorname..."/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Nachname: </td>
                        <td><input type="text" name="lastname" placeholder="Nachname..."/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Geburtsjahr: </td>
                        <td><input type="date" name="birthdate" placeholder="Geburtsjahr..."/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Verein: </td>
                        <td>
                            <select name="club" class="cel_m" id="">
                                <option selected disabled>--- Verein Ausw&auml;hlen ---</option>
                            ';

                                $strSQL = "SELECT * FROM vereine ORDER BY ort ASC";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs))
                                {
                                    echo '<option value="'.$row['kennzahl'].'">'.$row['verein'].' '.$row['ort'].'</option>';
                                }

                            echo '
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="ta_r">Bild ausw&auml;hlen: </td>
                        <td>'.FileButton("playerImg", "playerImg").'</td>
                    </tr>

                    <tr>
                        <td colspan=2 class="ta_c"><br><button type="submit" name="add_nwk">Hinzuf&uuml;gen</button></td>
                    </tr>
                </table>
            </form>
        ';

    }
    else
    {
        echo '<h1 class="stagfade1">O&Ouml; Nachwuchskader</h1>';

        if(CheckPermission("AddNWK")) echo AddButton("/nachwuchskader/neu");


        if(isset($_GET['edit']) AND CheckPermission("EditNWK"))
        {
            $pdat = FetchArray("nachwuchskader","id",$_GET['edit']);

            echo '
                <h2 class="stagfade2">Spieler bearbeiten</h2>
                <form action="/nachwuchskader" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <center>
                        <table>
                            <tr>
                                <td class="ta_r">Geschlecht</td>
                                <td>
                                    '.RadioButton("M&auml;nnlich", "gender",($pdat['gender']=="M"),"M").'
                                    '.RadioButton("Weiblich", "gender",($pdat['gender']=="W"),"W").'
                                </td>
                            </tr>
                            <tr>
                                <td class="ta_r">Vorname: </td>
                                <td><input type="text" name="firstname" placeholder="Vorname..." value="'.$pdat['firstname'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Nachname: </td>
                                <td><input type="text" name="lastname" placeholder="Nachname..." value="'.$pdat['lastname'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Geburtsjahr: </td>
                                <td><input type="date" name="birthdate" placeholder="Geburtsjahr..." value="'.$pdat['birthdate'].'"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Verein: </td>
                                <td>
                                    <select name="club" class="cel_m" id="">
                                        <option selected disabled>--- Verein Ausw&auml;hlen ---</option>
                                    ';

                                        $strSQL = "SELECT * FROM vereine ORDER BY ort ASC";
                                        $rs=mysqli_query($link,$strSQL);
                                        while($row=mysqli_fetch_assoc($rs))
                                        {
                                            echo '<option value="'.$row['kennzahl'].'" '.(($pdat['club']==$row['kennzahl']) ? 'selected' : '').'>'.$row['verein'].' '.$row['ort'].'</option>';
                                        }

                                    echo '
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="ta_r">Bild ausw&auml;hlen: </td>
                                <td>'.FileButton("playerImg", "playerImg").'</td>
                            </tr>

                            <tr>
                                <td colspan=2 class="ta_c"><br><button type="submit" name="add_nwk">&Auml;nderungen Speichern</button></td>
                            </tr>
                        </table>
                    </center>
                </form>
                <hr>
            ';
        }




        echo '
        <h3 style="color: blue">Burschen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
        ';
        $minYear = date("Y-m-d") - 18;
        $maxYear = date("Y-m-d") - 15;
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
        ';
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: blue">Burschen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
        ';
        $minYear = date("Y-m-d") - 14;
        $maxYear = date("Y-m-d") - 13;
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
        ';
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: blue">Burschen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
        ';
        $minYear = date("Y-m-d") - 12;
        $maxYear = date("Y-m-d") - 11;
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthdate >= '$minYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
        ';
        $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthdate >= '$minYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/nachwuchskader/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.Fetch("vereine","verein","kennzahl",$row['club']).' '.Fetch("vereine","ort","kennzahl",$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/nachwuchskader?edit=".$row['id'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","nachwuchskader",$row['id'],true);
                    echo '
                    </div>
                </div>
            ';
        }

        echo PageContent("1",CheckPermission("ChangeContent"));

    }
    include("footer.php");
?>