<?php

    require("header.php");

    $players = MySQL::Cluster("SELECT * FROM members WHERE active = '0' OR SUBSTRING(playerID,1,3) = 'TMP'");

    $isPresent = False;
    foreach($players as $p)
    {
        $isPresent = False;

        if(MySQL::Exist("SELECT * FROM members_spielerranglisten WHERE memberID = ?",'s',$p['id'])) $isPresent = True;
        if(MySQL::Exist("SELECT * FROM members_ooebvrl WHERE memberID = ?",'s',$p['id'])) $isPresent = True;
        if(MySQL::Exist("SELECT * FROM members_trainingsgruppen WHERE memberID = ?",'s',$p['id'])) $isPresent = True;
        if(MySQL::Exist("SELECT * FROM members_nachwuchskader WHERE memberID = ?",'s',$p['id'])) $isPresent = True;

        if(!$isPresent)
        {
            echo 'DELETED '.$p['id'].' ('.$p['playerID'].')<br>';
            MySQL::NonQuery("DELETE FROM members WHERE id = ?",'s',$p['id']);
        }
    }


?>