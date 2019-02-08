<?php
    require("header.php");
    PageTitle("Vorstand");

    if(isset($_POST['add_vorstand_member']))
    {
        $name = $_POST['name'];
        $fields = $_POST['fields'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        MySQL::NonQuery("INSERT INTO vorstand (darstellung,name,bereich,email,telefon) VALUES ('box',?,?,?,?)",'@s',$name,$fields,$email,$phone);

        FileUpload("content/vorstand/","image","","","UPDATE vorstand SET foto = 'FNAME' WHERE name = '$name'");

        Redirect("/vorstand");
        die();
    }

    if(isset($_POST['updateVorstand']))
    {
        $id = $_POST['updateVorstand'];
        $name = $_POST['name'];
        $bereich = $_POST['bereich'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];

        MySQL::NonQuery("UPDATE vorstand SET name = ?, bereich = ?, email = ?, telefon = ? WHERE id = ?",'@s',$name,$bereich,$email,$telefon,$id);

        Redirect(ThisPage());
        die();
    }

    if(isset($_POST['updateVorstandList']))
    {
        $b1_NW = $_POST['box1'];
        $b2_LG = $_POST['box2'];
        $b3_LA = $_POST['box3'];
        $b4_SG = $_POST['box4'];

        MySQL::NonQuery("UPDATE vorstand SET list_content = ? WHERE bereich = 'Nachwuchsarbeitskreis' AND darstellung = 'list'",'@s',$b1_NW);
        MySQL::NonQuery("UPDATE vorstand SET list_content = ? WHERE bereich = 'Ligagremium' AND darstellung = 'list'",'@s',$b2_LG);
        MySQL::NonQuery("UPDATE vorstand SET list_content = ? WHERE bereich = 'Ligaausschuss' AND darstellung = 'list'",'@s',$b3_LA);
        MySQL::NonQuery("UPDATE vorstand SET list_content = ? WHERE bereich = 'Schiedsgericht' AND darstellung = 'list'",'@s',$b4_SG);

        Redirect(ThisPage());
        die();
    }

    if(isset($_GET['neu']) AND CheckRank() == "administrative" AND CheckPermission("AddVorstand"))
    {
        echo '<h2 class="stagfade1">Vorstandsmitglied hinzuf&uuml;gen</h2>';

        echo '
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td class="ta_r">Name: </td>
                        <td><input type="text" name="name" placeholder="Vor- & Nachname"/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Bereiche: </td>
                        <td><textarea name="fields" placeholder="Bereiche..."></textarea></td>
                    </tr>
                    <tr>
                        <td class="ta_r">E-Mail: </td>
                        <td><input type="email" name="email" placeholder="E-Mail..."/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Telefon: </td>
                        <td><input type="tel" name="phone" placeholder="Telefon..."/></td>
                    </tr>
                    <tr>
                        <td class="ta_r">Foto: </td>
                        <td>'.FileButton('image','image').'</td>
                    </tr>
                    <tr>
                        <td colspan=2><button type="submit" name="add_vorstand_member">Hinzuf&uuml;gen</button></td>
                    </tr>
                </table>
            </form>
        ';
    }
    else
    {

        echo '
            <h1 class="stagfade1">Vorstand</h1>
        ';

        if(CheckPermission("AddVorstand")) echo AddButton("/vorstand/neu");

        echo '

            <p>'.PageContent('1',CheckPermission("ChangeContent")).'</p>
            <br>
            <center>
        ';

        $strSQL = "SELECT * FROM vorstand WHERE darstellung = 'box'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if(isset($_GET['editBox']) AND isset($_GET['editSC']) AND $_GET['editSC']==$row['id'] AND CheckPermission("EditVorstand"))
            {
                echo '
                    <div class="vmember_container">
                        <form action="'.ThisPage("!editSC").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="vmember_img" style="background: #FFFFFF">
                                <br><br>
                                Profilbild
                                <br>
                                '.FileButton("profileImg","profileImg").'
                            </div>
                            <div class="vmember_content">
                                <input name="name" type="text" style="width: 136px; margin:3px 0px;" class="cel_h20" value="'.$row['name'].'" placeholder="Name"/>
                                <textarea name="bereich" style="width: 136px; margin:3px 0px; resize: vertical;">'.$row['bereich'].'</textarea>
                                <input name="email" type="text" style="width: 136px; margin:3px 0px;" class="cel_h20" value="'.$row['email'].'" placeholder="E-Mail"/>
                                <input name="telefon" type="text" style="width: 136px; margin:3px 0px;" class="cel_h20" value="'.$row['telefon'].'" placeholder="Mobiltelefon"/>
                                <button name="updateVorstand" type="submit" value="'.$row['id'].'" class="cel_h25" style="padding: 0 3px; margin: 0px; margin-bottom: 8px; width: 136px;">Aktualisieren</button>
                            </div>
                        </form>
                    </div>
                ';
            }
            else
            {
                echo '
                    <div class="vmember_container">
                        <div class="vmember_img">
                            <img src="/content/vorstand/'.$row['foto'].'" alt="'.$row['name'].'" class="user_img_m"/>
                        </div>
                        <div class="vmember_content">
                            <b>'.$row['name'].'</b>
                            <br>
                            <i>'.nl2br($row['bereich']).'</i>
                            <br>
                            <a href="mailto:'.$row['email'].'">E-Mail senden</a>
                            <br>
                            <span>
                                Mobiltelefon:<br>
                                '.$row['telefon'].'
                            </span>
                        </div>
                        ';

                        if(CheckPermission("EditVorstand"))
                        {
                            echo '<span style="float: right;margin-right: 5px;"> '.EditButton(ThisPage("!editContent","!editSC","!editBox","!editList","+editSC=".$row['id'],"+editBox"),true).' </span>';
                        }

                        if(CheckPermission("DeleteVorstand"))
                        {
                            echo '<span style="float: right;margin-right: 5px;"> '.DeleteButton("Vorstand","vorstand",$row['id'],true).' </span>';
                        }

                        echo '
                    </div>
                ';
            }
        }

        echo '
        </center>
        <hr style="margin: 10px 0 10px 0">
        <center>
        ';

        if(isset($_GET['editList']) AND isset($_GET['editSC']) AND CheckPermission("EditVorstand"))
        {
            echo '<form action="'.ThisPage("!editContent","!editSC","!editList","!editBox").'" method="post" accept-charset="utf-8" enctype="multipart/form-data">';
        }

        $i=1;
        $strSQL = "SELECT bereich, list_content FROM vorstand WHERE darstellung = 'list'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            if(isset($_GET['editList']) AND isset($_GET['editSC']) AND CheckPermission("EditVorstand"))
            {
                echo '
                    <div class="vadditional_info">
                    <b>'.$row['bereich'].'</b><br>
                    <textarea style="resize: vertical; width: 200px; height: 300px;" name="box'.$i++.'">'.$row['list_content'].'</textarea>
                    </div>
                ';
            }
            else
            {
                echo '
                    <div class="vadditional_info">
                    <b>'.$row['bereich'].'</b><br>
                    '.nl2br($row['list_content']).'
                    </div>
                ';
            }
        }

        if(isset($_GET['editList']) AND isset($_GET['editSC']) AND CheckPermission("EditVorstand"))
        {
            echo '
                <br><button type="submit" name="updateVorstandList">Aktualisieren</button>
                <br><a href="'.ThisPage("!editContent","!editSC","!editList","!editBox").'"><button type="button">&Auml;nderungen verwerfen</button></a>
                </form>
            ';

        }
        else if(CheckPermission("EditVorstand"))
        {
            echo '<span style="margin-right: 5px;"> '.EditButton(ThisPage("!editContent","!editSC","!editBox","!editList","+editSC","+editList")).' </span>';
        }

        echo '</center>';
    }


    include("footer.php");
?>

