<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    session_start();

    require("headerincludes.php");

    if(isset($_POST['addMember']))
    {
        $playerID = $_POST['playerID'];

        if(!MySQL::Exist("SELECT id FROM members WHERE playerID = ?",'s',$playerID))
        {
            $id = uniqid();
            $clubID = $_POST['club'];
            $gender = $_POST['gender'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $birthdate = $_POST['birthdate'];

            MySQL::NonQuery("INSERT INTO members (id, clubID, playerID, gender, firstname, lastname, birthdate) VALUES (?,?,?,?,?,?,?)",'@s',$id,$clubID,$playerID,$gender,$firstname,$lastname,$birthdate);
            FileUpload("content/members/","playerImg","","","UPDATE members SET image = 'FNAME' WHERE id = '$id'",uniqid());
        }
        else
        {
            $id = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$playerID);
        }


        if($_GET['assignUser'] == 'tg')
        {
            $trainingsgruppenID = $_POST['trainingsgruppenID'];
            MySQL::NonQuery("INSERT INTO members_trainingsgruppen (id,tgID,memberID) VALUES ('',?,?)",'ss',$trainingsgruppenID,$id);
        }

        if($_GET['assignUser'] == 'nwk')
        {
            MySQL::NonQuery("INSERT INTO members_nachwuchskader (id,memberID) VALUES ('',?)",'s',$id);
        }

        Redirect(ThisPage());
        die();
    }

   echo '
        <!DOCTYPE html>
        <html>
            <head>
    ';
        require("headerlinks.php");
        echo Dynload::Link();

    echo '
            </head>
            <body>
                <div class="iframe_content">

    ';

    echo DynLoad::Start(1);

    echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <center>

                        <input type="hidden" id="outPlayerIDCheck"/>

                        <script type="text/javascript">
                            setInterval(function() {

                                if(document.getElementById("inputPlayerID").value != "")
                                {
                                    if(document.getElementById("outPlayerIDCheck").value == "1")
                                    {
                                        document.getElementById("inputGenderM").disabled = true;
                                        document.getElementById("inputGenderF").disabled = true;
                                        document.getElementById("inputFirstName").disabled = true;
                                        document.getElementById("inputLastName").disabled = true;
                                        document.getElementById("inputBirthyear").disabled = true;
                                        document.getElementById("inputClub").disabled = true;

                                        document.getElementById("checkMemberNotificationExists").style.display = "table-row";
                                        document.getElementById("checkMemberNotificationExistsNot").style.display = "none";
                                    }
                                    else
                                    {
                                        document.getElementById("inputGenderM").disabled = false;
                                        document.getElementById("inputGenderF").disabled = false;
                                        document.getElementById("inputFirstName").disabled = false;
                                        document.getElementById("inputLastName").disabled = false;
                                        document.getElementById("inputBirthyear").disabled = false;
                                        document.getElementById("inputClub").disabled = false;

                                        document.getElementById("checkMemberNotificationExistsNot").style.display = "table-row";
                                        document.getElementById("checkMemberNotificationExists").style.display = "none";
                                    }
                                }
                                else
                                {
                                    document.getElementById("inputGenderM").disabled = true;
                                    document.getElementById("inputGenderF").disabled = true;
                                    document.getElementById("inputFirstName").disabled = true;
                                    document.getElementById("inputLastName").disabled = true;
                                    document.getElementById("inputBirthyear").disabled = true;
                                    document.getElementById("inputClub").disabled = true;
                                }



                            }, 200);
                        </script>


                        <table>
                            <tr>
                                <td class="ta_r">Spielernummer: </td>
                                <td colspan=2><input type="number" name="playerID" placeholder="Spielernummer..." id="inputPlayerID"
                                onchange="
                                DynLoadExist(1,this,\'outPlayerIDCheck\',\'SELECT * FROM members WHERE playerID = ??\');
                                "/></td>
                            </tr>
                            <tr style="display: none" id="checkMemberNotificationExists">
                                <td colspan=3>
                                    <center><span style="color: #32CD32">Spieler gefunden!</span></center>
                                </td>
                            </tr>
                            <tr style="display: none" id="checkMemberNotificationExistsNot">
                                <td colspan=3>
                                    <center><span style="color: #8B0000">Spieler noch nicht bekannt.</span></center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=3><hr></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Geschlecht: </td>
                                <td>'.RadioButton("M&auml;nnlich", "gender",true,"M","inputGenderM").'</td>
                                <td>'.RadioButton("Weiblich", "gender",false,"W","inputGenderF").'</td>
                            </tr>
                            <tr>
                                <td class="ta_r">Vorname: </td>
                                <td colspan=2><input type="text" name="firstname" placeholder="Vorname..." id="inputFirstName"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Nachname: </td>
                                <td colspan=2><input type="text" name="lastname" placeholder="Nachname..." id="inputLastName"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Geburtsdatum: </td>
                                <td colspan=2><input type="date" name="birthdate" placeholder="Geburtsdatum..." id="inputBirthyear"/></td>
                            </tr>
                            <tr>
                                <td class="ta_r">Verein: </td>
                                <td colspan=2>
                                    <select name="club" class="cel_m" id="inputClub">
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
                                <td colspan=2>'.FileButton("playerImg", "playerImg").'</td>
                            </tr>
                            ';

                            if(isset($_GET['assignUser']) AND $_GET['assignUser'] == 'tg')
                            {
                                echo '<input type="hidden" name="trainingsgruppenID" value="'.$_GET['tgID'].'"/>';
                            }

                            echo '

                            <tr>
                                <td colspan=3 class="ta_c"><br><button type="submit" name="addMember">Spieler eintragen und hinzuf&uuml;gen</button></td>
                            </tr>
                        </table>

                        </center>
                    </form>
                </div>
            </body>
        </html>
    ';
?>

