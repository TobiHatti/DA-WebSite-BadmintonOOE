<?php
    require("header.php");

    if(isset($_POST['updateMembers']))
    {
        $id = $_POST['updateMembers'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $birthdate= $_POST['birthdate'];
        $number=$_POST['number'];
        $gender = $_POST['gender'];

        MySQL::NonQuery("UPDATE members SET firstname = ?, lastname = ?, birthdate = ?, playerID = ?, gender = ? WHERE id = ?",'@s',$firstname,$lastname,$birthdate,$number,$gender,$id);

        FileUpload("content/members/","image","","","UPDATE members SET img = 'FNAME' WHERE id = '$id'",uniqid());

        Redirect("/mitglieder/anzeigen?verein=".$_GET['verein']);
        die();
    }


    if(CheckRank() == "administrative" AND isset($_GET['section']))
    {
        if($_GET['section'] == 'neu')
        {
            echo '<h2>Spieler eintragen</h2>';

            echo '<iframe src="/memberAddFrame" frameborder="0" style="width: 100%; height: 480px;"></iframe>';
        }
        else if($_GET['section'] == 'anzeigen')
        {
            echo '<h2>Spieler anzeigen</h2>';

            $clubListPerm = MySQL::Cluster("SELECT * FROM vereine WHERE isOOEclub = '1' ORDER BY ort,verein ASC");
            $clubListTemp = MySQL::Cluster("SELECT * FROM vereine WHERE isOOEclub = '0' ORDER BY ort,verein ASC");

            echo '
                <div style="float: right;">
                    <select onchange="RedirectSelectBoxParam(this,\'/mitglieder/anzeigen?verein=??\');">
                        <option value="">--- Verein ausw&auml;hlen ---</option>

                        <optgroup label="Vereine aus O&Ouml;">
                    ';
                        foreach($clubListPerm as $clubData) echo '<option value="'.$clubData['kennzahl'].'" '.((isset($_GET['verein']) AND $_GET['verein'] == $clubData['kennzahl']) ? 'selected' : '').'>'.$clubData['kennzahl'].' - '.$clubData['verein'].' '.$clubData['ort'].'</value>';
                    echo '
                        </optgroup>
                        <optgroup label="Andere Vereine">
                    ';
                        foreach($clubListTemp as $clubData) echo '<option value="'.$clubData['kennzahl'].'" '.((isset($_GET['verein']) AND $_GET['verein'] == $clubData['kennzahl']) ? 'selected' : '').'>'.$clubData['kennzahl'].' - '.$clubData['verein'].' '.$clubData['ort'].'</value>';
                    echo '
                        </optgroup>
                    </select>
                </div>
                <br><br><br>
            ';

            if(isset($_GET['verein']) AND $_GET['verein'] != '')
            {
                $clubMembers = MySQL::Cluster("SELECT * FROM members WHERE clubID = ?",'s',$_GET['verein']);

                $playersNoGender = MySQL::Count("SELECT * FROM members WHERE clubID = ? AND gender = ''",'s',$_GET['verein']);
                $playersNoNumber = MySQL::Count("SELECT * FROM members WHERE clubID = ? AND SUBSTRING(playerID,1,3) = 'TMP'",'s',$_GET['verein']);


                if($playersNoGender !=0 OR $playersNoNumber !=0)
                {
                    echo '<h3>Warnung: Es gibt Spieler mit fehlenden Informationen!</h3>';

                    if($playersNoNumber != 0)
                    {
                        echo '<h4>'.$playersNoNumber.' Spieler ohne Mitglieds-Nummer</h4>';
                        echo '
                            Tragen Sie so bald wie m&ouml;glich die Mitgliedsnummer dieses Spielers ein, um Doppelte eintr&auml;ge zu vermeiden!<br><br>
                            <b>Info:</b> Sollte bereits ein Spieler mit Mitgliedsnummer passend zu dem Spieler ohne Mitgliedsnummer eingetragen sein,<br>
                            k&ouml;nnen Sie diesen mit "Zusammenf&uuml;hren" (" <i class="fas fa-compress"></i> ") auf den Spieler mit Mitgliedsnummer &uuml;bertragen.<br>
                            <span style="color: #BD0000"><b>Das l&ouml;schen des Spielers ohne Mitgliedsnummer soll in diesem Fall vermieden werden!</b></span><br><br>
                        ';

                        $playerData = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND SUBSTRING(playerID,1,3) = 'TMP'",'s',$_GET['verein']);
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNN",true);

                        echo '<br><br>';
                    }

                    if($playersNoGender != 0)
                    {
                        echo '<h4>'.$playersNoGender.' Spieler mit fehlendem Geschlecht</h4>';
                        echo 'Ohne Angabe des Geschlechts scheint der Spieler u.a. nicht in der Auswahl zur Spielerreihung auf!<br>';

                        $playerData = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND gender = ''",'s',$_GET['verein']);
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNG",true);

                        echo '<br><br><hr>';
                    }
                }

                foreach($clubMembers as $member)
                {
                    echo PlayerDisplayClubInfo($member,"",true);
                }



            }
            else
            {
                echo '<h3>Bitte w&auml;hlen Sie einen Verein aus</h3>';
            }
        }

    }

    include("footer.php");
?>