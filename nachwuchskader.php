<?php
    require("header.php");

    // Note:
    // This file was earlier used dor "Trainingsgruppen"
    // Therefore the old descriptions are still used.

    if(isset($_POST['createNewGroup']))
    {
        $groupName = $_POST['groupName'];
        $groupFilename = SReplace($groupName);

        $showLastEdit = (isset($_POST['showLastEdit']) ? 1 : 0);
        $lastEdit = date("Y-m-d");

        MySQL::NonQuery("INSERT INTO trainingsgruppen (trainingsgruppe,tgURL,showLastEdit,lastEdit) VALUES (?,?,?,?)",'ssss',$groupName,$groupFilename,$showLastEdit,$lastEdit);
        Redirect("/nachwuchskader");
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
        Redirect("/nachwuchskader");
        die();
    }

    if(isset($_POST['updateRowing']))
    {
        $tgID = $_POST['updateRowing'];

        $reihungComboNWK = $_POST['sortOutputNWK'];

        foreach(explode('||',$reihungComboNWK) as $rp)
        {
            $rp = explode('##',$rp);
            if(!isset($rp[1])) continue;

            $memberID = MySQL::Scalar("SELECT id FROM members WHERE playerID = ?",'s',$rp[1]);

            MySQL::NonQuery("UPDATE members_trainingsgruppen SET rank = ? WHERE memberID = ? AND tgID = ?",'@s',$rp[0],$memberID,$tgID);
        }

        Redirect("/nachwuchskader");
        die();

    }

    echo '<h1>O&Ouml; Nachwuchskader</h1><hr>';



    if(isset($_GET['eintragen']) AND CheckRank() == "administrative" AND CheckPermission("AddNWK"))
    {
        echo '<h3>Spieler zum Abschnitt "'.MySQL::Scalar("SELECT trainingsgruppe FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']).'" hinzuf&uuml;gen</h3>';

        $tgID = MySQL::Scalar("SELECT id FROM trainingsgruppen WHERE tgURL = ?",'s',$_GET['eintragen']);

        echo ' <iframe src="/memberAddFrame?assignUser=tg&tgID='.$tgID.'" frameborder="0" style="width: 100%; height: 400px;"></iframe>';
    }
    else if(isset($_GET['newGroup']) AND CheckRank() == "administrative" AND CheckPermission("AddNWK"))
    {
        echo '<h3>Neuen Abschnitt erstellen</h3>';

        echo '
            <br><br>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Abschnittname: </td>
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
    else if(isset($_GET['editGroup']) AND CheckRank() == "administrative" AND CheckPermission("EditNWK"))
    {
        $tgData = MySQL::Row("SELECT * FROM trainingsgruppen WHERE id = ?",'s',$_GET['groupID']);
        echo '<h3>Abschnitt bearbeiten</h3>';

        echo '
            <br><br>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Abschnittname: </td>
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
        echo PageContent('2',CheckPermission("ChangeContent")).'<br>';


        if(CheckPermission("AddNWK")) echo '<a href="/nachwuchskader/neue-gruppe">Neue Gruppe erstellen</a><br>';



        $trainingsgruppen = MySQL::Cluster("SELECT * FROM trainingsgruppen");
        foreach($trainingsgruppen as $tg)
        {
            echo '<h3>'.$tg['trainingsgruppe'].' '.($tg['showLastEdit'] == 1 ? ('(Stand: '.date_format(date_create($tg['lastEdit']),"d.m.Y").')') : '').' <sub>'.(CheckPermission("EditNWK") ? EditButton("/nachwuchskader/gruppe-bearbeiten/".$tg['id'],true) : '').' '.(CheckPermission("DeleteNWK") ? DeleteButton("NWK","trainingsgruppen",$tg['id'],true) : '').'</sub></h3><hr>';

            if(isset($_GET['anordnen']) AND $_GET['anordnen'] == $tg['tgURL'])
            {
                echo '
                    <b>Spieler anordnen:</b><br>
                    Mit der Maus anglicken und verschieben (Drag\'n\'drop)<br>

                    <script>
                        $( function() {
                            $( "#sortListNWK" ).sortable();
                            $( "#sortListNWK" ).disableSelection();
                        } );

                        window.setInterval(function(){
                            CheckSortableListStateRev2("sortListNWK","outputNWK");
                        }, 100);

                    </script>
                ';

                $tgDataCount = MySQL::Count("SELECT * FROM members_trainingsgruppen INNER JOIN members ON members_trainingsgruppen.memberID = members.id WHERE members_trainingsgruppen.tgID = ?",'s',$tg['id']);
                echo '<ul class="dragSortList_posNumbers">';
                for($i=1 ; $i <= $tgDataCount ; $i++) echo '<li>'.$i.'</li>';
                echo '</ul>';

                $tgData = MySQL::Cluster("SELECT *,members_trainingsgruppen.id AS mbID FROM members_trainingsgruppen INNER JOIN members ON members_trainingsgruppen.memberID = members.id WHERE members_trainingsgruppen.tgID = ? ORDER BY rank ASC",'i',$tg['id']);

                $i=1;
                echo '<ul class="dragSortList_values" id="sortListNWK">';
                foreach($tgData as $memberData) echo '<li type="text" data-memberID="'.$memberData['playerID'].'">'.$memberData['lastname'].'</b> '.$memberData['firstname'].'</li>';
                echo '</ul>

                ';

                echo '
                    <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <input type="hidden" name="sortOutputNWK" id="outputNWK"/>

                        <button type="submit" name="updateRowing" value="'.$tg['id'].'">Reihung speichern</button>
                    </form>
                    <br><br>
                ';
            }
            else
            {
                if(CheckPermission("AddNWTG")) echo '<a href="/nachwuchskader/'.$tg['tgURL'].'/eintragen">&#65291; Spieler hinzuf&uuml;gen</a><br>';
                if(CheckPermission("EditNWTG")) echo '<a href="/nachwuchskader/'.$tg['tgURL'].'/anordnen">&#8634; Spieler anordnen</a><br>';

                $tgData = MySQL::Cluster("SELECT *,members_trainingsgruppen.id AS mbID FROM members_trainingsgruppen INNER JOIN members ON members_trainingsgruppen.memberID = members.id WHERE members_trainingsgruppen.tgID = ? ORDER BY rank ASC",'i',$tg['id']);
                foreach($tgData as $memberData)
                {

                    echo '
                        <div class="nwkaderCard" style="border-left: 5px groove '.(($memberData['gender']=='M') ? 'blue' : (($memberData['gender']=='F') ? 'red' : 'black')).'">
                            <img src="'.(($memberData['image']!="") ? ('/content/members/'.$memberData['image']) : '/content/user.png' ).'" alt="" />
                            <div>
                                <b>'.$memberData['lastname'].'</b> '.$memberData['firstname'].'<br>
                                ';

                                if($memberData['birthdate'] != "0000-00-00")  echo 'Geb.: '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($memberData['birthdate']))).'<br>';

                                echo '
                                <span style="color: #696969">'.MySQL::Scalar("SELECT CONCAT_WS(' ',verein,ort) FROM vereine WHERE kennzahl = ?",'s',$memberData['clubID']).'</span>
                            </div>
                            <div style="position: absolute; bottom: 0px; right: 0px; height: 20px;">
                            ';
                            if(CheckPermission("EditNWK")) echo EditButton("/mitglieder/bearbeiten/NWK/".$memberData['playerID'],true);
                            if(CheckPermission("DeleteNWK")) echo DeleteButton("NWK","members_trainingsgruppen",$memberData['mbID'],true);
                            echo '
                            </div>
                        </div>
                    ';
                }
                echo '<br><br><br>';
            }


        }

        echo PageContent('1',CheckPermission("ChangeContent"));
    }


    include("footer.php");
?>