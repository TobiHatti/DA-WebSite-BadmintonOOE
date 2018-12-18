<?php
    include("data/mysql.lib.new.php");

    $rows = MySQL::Cluster("SELECT * FROM settings LIMIT 0,3");

    foreach($rows as $row)
    {
        echo $row['setting'].' : '.$row['value'].'<br>';
    }
?>



