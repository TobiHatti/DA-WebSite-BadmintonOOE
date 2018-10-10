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
        $club = Fetch("users","club","id",$_SESSION['userID']);

        MySQLNonQuery("INSERT INTO members (id,club,number,gender,firstname,lastname,birthdate) VALUES ('$uid','$club','$number','$gender','$firstname','$lastname','$birthdate')");

        FileUpload("content/members/","image","","","UPDATE members SET img = 'FNAME' WHERE id = '$uid'",uniqid());

        Redirect("/verein-info/mitglieder");
        die();
    }

    if(CheckRank() == "clubmanager")
    {
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

                $strSQL = "SELECT * FROM members ORDER BY birthdate DESC";
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

                echo '<br><br><a href="/verein-info/mitglieder?neu">Neuen Spieler hinzufügen</a> ';
            }
        }
        else
        {


            $cdata = FetchArray("vereine","kennzahl",Fetch("users","club","id",$_SESSION['userID']));

            echo '
                <h2 class="stagfade1">'.$cdata['verein'].' '.$cdata['ort'].'</h2>
                <hr>

                <h3>Spieler</h3>

            ';

            $strSQL = "SELECT * FROM members ORDER BY birthdate DESC";
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