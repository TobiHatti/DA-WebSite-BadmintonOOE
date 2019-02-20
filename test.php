<?php
    require("header.php");

    /*
    $rows = MySQL::Cluster("SELECT * FROM permission_list");
    foreach($rows as $row)
    {
        // Tobi 24
        // Paul 25
        //MySQL::NonQuery("INSERT INTO permissions (user_id,permission,allowed) VALUES ('25',?,'1')",'s',$row['permission']);
    }

    */



    $articleURL = uniqid();

    echo DebugMySQL::NonQuery("INSERT INTO news (article_url,title) VALUES (?,?)",'ss',$articleURL,$articleURL)


?>



