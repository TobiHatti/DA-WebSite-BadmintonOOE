<?php

    require("header.php");

    $srlData = MySQL::Cluster("SELECT * FROM members_spielerranglisten WHERE year = '2018-2019' AND memberID IN (SELECT memberID FROM members_spielerranglisten WHERE year = '2018-2019' GROUP BY memberID,assignedClubID HAVING COUNT(id) > 1)  ");

    foreach($srlData as $srl)
    {
        $player = MySQL::Row("SELECT * FROM members WHERE id = ?",'s',$srl['memberID']);
        echo $srl['memberID'].' '.$srl['assignedClubID'].' '.$player['lastname'].' '.$player['firstname'].'<br>';
    }

?>
