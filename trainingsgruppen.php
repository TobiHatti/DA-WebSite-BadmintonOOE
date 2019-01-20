<?php
    require("header.php");

    if(isset($_POST['createNewGroup']))
    {
        $groupName = $_POST['groupName'];
        $groupFilename = SReplace($groupName);

        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        MySQL::NonQuery("INSERT INTO trainingsgruppen (id,trainingsgruppe,tgURL,showLastEdit,lastEdit) VALUES ('',?,?,?,?)",'ssss',$groupName,$groupFilename,$showLastEdit,$lastEdit);
        Redirect("/trainingsgruppen");
        die();
    }

    if(isset($_POST['editGroup']))
    {
        $groupID = $_POST['id'];
        $groupName = $_POST['groupName'];
        $groupFilename = SReplace($groupName);

        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        MySQL::NonQuery("UPDATE trainingsgruppen SET trainingsgruppe = ?, tgURL = ?, showLastEdit = ?, lastEdit = ? WHERE id = ?",'sssss',$groupName,$groupFilename,$showLastEdit,$lastEdit,$groupID);
        Redirect("/trainingsgruppen");
        die();
    }

    echo '<h1>Trainingsgruppen</h1><hr>';

    if(isset($_GET['eintragen']))
    {
        echo '<h3>Spieler zu Gruppe "'.MySQL::Scalar("SELECT trainingsgruppe FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']).'" hinzuf&uuml;gen</h3>';

        $tgID = MySQL::Scalar("SELECT id FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']);

        echo ' <iframe src="/memberAddFrame?assignUser=tg&tgID='.$tgID.'" frameborder="0" style="width: 100%; height: 400px;"></iframe>';
    }
    else if(isset($_GET['newGroup']))
    {
        echo '<h3>Neue Gruppe erstellen</h3>';

        echo '
            <br><br>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Gruppenname: </td>
                        <td><input type="text" name="groupName" placeholder="Gruppenname..." required/></td>
                    </tr>
                    <tr>
                        <td>Letzte Bearbeitung<br>anzeigen:</td>
                        <td><center>'.Tickbox("showLastEdit","showLastEdit","",true).'</center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type="submit" name="createNewGroup">Gruppe erstellen</button></td>
                    </tr>
                </table>
            </form>
        ';
    }
    else if(isset($_GET['editGroup']))
    {
        $tgData = MySQL::Row("SELECT * FROM trainingsgruppen WHERE id = ?",'s',$_GET['groupID']);
        echo '<h3>Gruppe bearbeiten</h3>';

        echo '
            <br><br>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Gruppenname: </td>
                        <td><input type="text" name="groupName" placeholder="Gruppenname..." value="'.$tgData['trainingsgruppe'].'" required/></td>
                    </tr>
                    <tr>
                        <td>Letzte Bearbeitung<br>anzeigen:</td>
                        <td><center>'.Tickbox("showLastEdit","showLastEdit","",($tgData['showLastEdit'] == 1) ? true : false).'</center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <input type="hidden" name="id" value="'.$_GET['groupID'].'"/>
                        <td><button type="submit" name="editGroup">Gruppe aktualisieren</button></td>
                    </tr>
                </table>
            </form>
        ';
    }
    else
    {
        if(CheckPermission("AddNWTG")) echo '<a href="/trainingsgruppen/neue-gruppe">Neue Gruppe erstellen</a><br>';

        $trainingsgruppen = MySQL::Cluster("SELECT * FROM trainingsgruppen");
        foreach($trainingsgruppen as $tg)
        {
            echo '<h3><sub>Gruppe</sub>&nbsp;&nbsp;&nbsp;'.$tg['trainingsgruppe'].' '.($tg['showLastEdit'] == 1 ? ('(Stand: '.date_format(date_create($tg['lastEdit']),"d.m.Y").')') : '').' <sub>'.(CheckPermission("EditNWTG") ? EditButton("/trainingsgruppen/gruppe-bearbeiten/".$tg['id'],true) : '').' '.(CheckPermission("DeleteNWTG") ? DeleteButton("NWTG","trainingsgruppen",$tg['id'],true) : '').'</sub></h3><hr>';
            if(CheckPermission("AddNWTG")) echo '<a href="/trainingsgruppen/'.$tg['tgURL'].'/eintragen">Spieler hinzuf&uuml;gen</a><br>';

            $tgData = MySQL::Cluster("SELECT *,members_trainingsgruppen.id AS mbID FROM members_trainingsgruppen INNER JOIN members ON members_trainingsgruppen.memberID = members.id WHERE members_trainingsgruppen.tgID = ?",'i',$tg['id']);
            foreach($tgData as $memberData)
            {

                echo '
                    <div class="nwkaderCard" style="border-left: 5px groove '.(($memberData['gender']=='M') ? 'blue' : (($memberData['gender']=='F') ? 'red' : 'black')).'">
                        <img src="'.(($memberData['image']!="") ? ('/content/members/'.$memberData['image']) : '/content/user.png' ).'" alt="" />
                        <div>
                            <b>'.$memberData['lastname'].'</b> '.$memberData['firstname'].'<br>
                            Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($memberData['birthdate']))).'<br>
                            <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$memberData['clubID']).'</span>
                        </div>
                        <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                        ';
                        if(CheckPermission("EditNWTG")) echo EditButton("/mitglieder/bearbeiten/NWTG/".$memberData['playerID'],true);
                        if(CheckPermission("DeleteNWTG")) echo DeleteButton("NWTG","members_trainingsgruppen",$memberData['mbID'],true);
                        echo '
                        </div>
                    </div>
                ';
            }
            echo '<br><br><br>';
        }
    }


    include("footer.php");
?>