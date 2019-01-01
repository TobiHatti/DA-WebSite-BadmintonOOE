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

        if(isset($_POST['add_nwk']))MySQL::NonQuery("INSERT INTO nachwuchskader (id,firstname,lastname,gender,club,birthdate) VALUES (?,?,?,?,?,?)",'@s',$id,$fn,$ln,$gender,$club,$birthdate);
        else
        {
            $id = $_POST["edit_nwk"];

            $strSQL = "UPDATE nachwuchskader SET
            gender = ?,
            firstname = ?,
            lastname = ?,
            birthdate = ?,
            club = ?
            WHERE id = ?";

            MySQL::NonQuery($strSQL,'@s',$gender,$fn,$ln,$birthdate,$club,$id);
        }

        FileUpload("/content/nachwuchskader/","playerImg","","","UPDATE nachwuchskader SET image = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect("/nachwuchskader");
        die();
    }

    if(isset($_GET["neu"]))
    {
        echo '<h2 class="stagfade1">O&Ouml; Nachwuchskader - Spieler hinzuf&uuml;gen</h2>';

        echo ' <iframe src="/memberAddFrame?assignUser=nwk" frameborder="0" style="width: 100%; height: 400px;"></iframe>';
    }
    else
    {
        echo '<h1 class="stagfade1">O&Ouml; Nachwuchskader</h1>';

        if(CheckPermission("AddNWK")) echo AddButton("/nachwuchskader/neu");


        echo '
        <h3 style="color: blue">Burschen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
        ';
        $minYear = date("Y-m-d",strtotime(date("Y-m-d").' -18 year'));
        $maxYear = date("Y-m-d",strtotime(date("Y-m-d").' -15 year'));
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'M' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U19 ('.(date("Y") - 18).' - '.(date("Y") - 15).')</h3>
        ';
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'W' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: blue">Burschen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
        ';
        $minYear = date("Y-m-d",strtotime(date("Y-m-d").' -14 year'));
        $maxYear = date("Y-m-d",strtotime(date("Y-m-d").' -13 year'));
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'M' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U15 ('.(date("Y") - 14).' - '.(date("Y") - 13).')</h3>
        ';
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'W' AND birthdate >= '$minYear' AND birthdate <= '$maxYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: blue">Burschen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
        ';
        $minYear = date("Y-m-d",strtotime(date("Y-m-d").' -12 year'));
        $maxYear = date("Y-m-d",strtotime(date("Y-m-d").' -11 year'));
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'M' AND birthdate >= '$minYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove blue;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }
        echo '

        <br><br>
        <h3 style="color: red">M&auml;dchen U13 ('.(date("Y") - 12).' - '.(date("Y") - 11).')</h3>
        ';
        $strSQL = "SELECT *,members_nachwuchskader.id AS mbID FROM members_nachwuchskader INNER JOIN members ON members_nachwuchskader.memberID = members.id WHERE gender = 'W' AND birthdate >= '$minYear'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <div class="nwkaderCard" style="border-left: 5px groove red;">
                    <img src="'.(($row['image']!="") ? ('/content/members/'.$row['image']) : '/content/user.png' ).'" alt="" />
                    <div>
                        <b>'.$row['lastname'].'</b> '.$row['firstname'].'<br>
                        Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['birthdate']))).'<br>
                        <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$row['club']).'</span>
                    </div>
                    <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                    ';
                    if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$row['playerID'],true);
                    if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_nachwuchskader",$row['mbID'],true);
                    echo '
                    </div>
                </div>
            ';
        }

        echo PageContent("1",CheckPermission("ChangeContent"));

    }
    include("footer.php");
?>