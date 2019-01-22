<?php
    require("header.php");

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

            $clubList = MySQL::Cluster("SELECT * FROM vereine ORDER BY ort, verein ASC");

            echo '
                <div style="float: right;">
                    <select onchange="RedirectSelectBoxParam(this,\'/mitglieder/anzeigen?verein=??\');">
                        <option value="">--- Verein ausw&auml;hlen ---</option>
                        ';
                        foreach($clubList AS $clubData) echo '<option value="'.$clubData['kennzahl'].'" '.($_GET['verein'] == $clubData['kennzahl'] ? 'selected' : '').'>'.$clubData['verein'].' '.$clubData['ort'].'</value>';
                        echo '
                    </select>
                </div>
                <br><br><br>
            ';

            if(isset($_GET['verein']))
            {
                $clubMembers = MySQL::Cluster("SELECT * FROM members WHERE clubID = ?",'s',$_GET['verein']);

                foreach($clubMembers as $member)
                {
                    echo PlayerDisplayClubInfo($member);
                }

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
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNN");

                        echo '<br><br>';
                    }

                    if($playersNoGender != 0)
                    {
                        echo '<h4>'.$playersNoGender.' Spieler mit fehlendem Geschlecht</h4>';
                        echo 'Ohne Angabe des Geschlechts scheint der Spieler u.a. nicht in der Auswahl zur Spielerreihung auf!<br>';

                        $playerData = MySQL::Cluster("SELECT * FROM members WHERE clubID = ? AND gender = ''",'s',$_GET['verein']);
                        foreach($playerData AS $player) echo PlayerDisplayClubInfo($player,"editNG");

                        echo '<br><br>';
                    }
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