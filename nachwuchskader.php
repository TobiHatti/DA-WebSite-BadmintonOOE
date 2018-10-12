<?php
    require("header.php");
    PageTitle("O\u00d6 Nachwuchskader");

    if(isset($_POST["add_nwk"]))
    {
        $fn = $_POST['firstname'];
        $ln = $_POST['lastname'];
        $gender = $_POST['gender'];
        $birthyear = $_POST['birthyear'];
        $club = $_POST['club'];

        MySQLNonQuery("INSERT INTO nachwuchskader (id,firstname,lastname,gender,club,birthyear) VALUES ('','$fn','$ln','$gender','$club','$birthyear')");

        Redirect(ThisPage());
    }
    if(isset($_GET["neu"])){

        echo '
        <form action="'.ThisPage("!editSC").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <table>
                <tr>
                 <tr>
                   <td>Bild ausw&auml;hlen</td>
                   <td>'.FileButton("post-name", "element-id", 1).'</td>
                 </tr>
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
        </form>
        ';

    }
    else
    {



    echo '<h1 class="stagfade1">O&Ouml; Nachwuchskader</h1>

    <h3 style="color: blue">Burschen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
    ';
    $minYear = date("Y") - 18;
    $maxYear = date("Y") - 15;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: blue">Burschen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
    ';
    $minYear = date("Y") - 14;
    $maxYear = date("Y") - 13;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear' AND birthyear <= '$maxYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: blue">Burschen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
    ';
    $minYear = date("Y") - 12;
    $maxYear = date("Y") - 11;
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'M' AND birthyear >= '$minYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove blue;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }
    echo '

    <br><br>
    <h3 style="color: red">M&auml;dchen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
    ';
    $strSQL = "SELECT * FROM nachwuchskader WHERE gender = 'W' AND birthyear >= '$minYear'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '
            <div class="nwkaderCard" style="border-left: 5px groove red;">
                <img src="/content/user.png" alt="" />
                <div>
                    <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                    Geb. '.$row['birthyear'].'<br>
                    <span style="color: #696969">'.$row['club'].'</span>
                </div>
            </div>
        ';
    }

    echo PageContent("1",CheckPermission("ChangeContent"));

   }
    include("footer.php");
?>