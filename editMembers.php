<?php
    require("header.php");

    if(isset($_POST['addMember']))
    {
        $playerID = $_POST['playerID'];
        $id = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$playerID);
        $clubID = $_POST['club'];
        $gender = $_POST['gender'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];


        MySQL::NonQuery("UPDATE members SET clubID = ?, playerID = ?, gender = ?, firstname = ?, lastname = ?, birthdate = ? WHERE id = ?",'@s',$clubID,$playerID,$gender,$firstname,$lastname,$birthdate,$id);
        FileUpload("content/members/","playerImg","","","UPDATE members SET image = 'FNAME' WHERE id = '$id'",uniqid());


        Redirect(ThisPage());
        die();
    }

    if(CheckPermission("Edit".$_GET['permissionSuffix']))
    {
      $memberData = MySQL::Row("SELECT * FROM members WHERE playerID = ?",'s',$_GET['memberID']);

      echo '
        <h2 class="stagfade2">Spieler bearbeiten</h2>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <center>
                <table>
                    <tr>
                        <td class="ta_r">Spielernummer: </td>
                        <td colspan=2><input type="number" name="playerID" placeholder="Spielernummer..." value="'.$memberData['playerID'].'"/></td>
                    </tr>
                    <tr>
                        <td colspan=3><hr></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Geschlecht: </td>
                        <td>'.RadioButton("M&auml;nnlich", "gender",($memberData['gender']=='M' ? true : false),"M","inputGenderM").'</td>
                        <td>'.RadioButton("Weiblich", "gender",($memberData['gender']=='W' ? true : false),"W","inputGenderF").'</td>
                    </tr>
                    <tr>
                        <td class="ta_r">Vorname: </td>
                        <td colspan=2><input type="text" name="firstname" placeholder="Vorname..." value="'.$memberData['firstname'].'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Nachname: </td>
                        <td colspan=2><input type="text" name="lastname" placeholder="Nachname..." value="'.$memberData['lastname'].'"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Geburtsdatum: </td>
                        <td colspan=2><input type="date" name="birthdate" placeholder="Geburtsdatum..." value="'.$memberData['birthdate'].'"/></td>
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
                                    echo '<option value="'.$row['kennzahl'].'" '.($row['kennzahl'] == $memberData['clubID'] ? 'selected' : '').'>'.$row['verein'].' '.$row['ort'].'</option>';
                                }

                            echo '
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan=2>
                            <center><img src="'.(($memberData['image']!="") ? ('/content/members/'.$memberData['image']) : '/content/user.png' ).'" alt="" style="max-width: 100px; max-height: 100px;"/></center>
                        </td>
                    </tr>
                    <tr>
                        <td class="ta_r">Bild ausw&auml;hlen: </td>
                        <td colspan=2><center>'.FileButton("playerImg", "playerImg").'</center></td>
                    </tr>
                    <tr>
                        <td colspan=3 class="ta_c"><br><button type="submit" name="updateMember">Spielerdaten aktualisieren</button></td>
                    </tr>
                </table>
            </center>
        </form>
      ';
    }

    include("footer.php");
?>