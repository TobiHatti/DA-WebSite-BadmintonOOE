<?php
    include("data/mysql.lib.new.php");

    $id=1;

    while($row = SQL::Query("SELECT * FROM users WHERE id NOT LIKE ?",'s',$id)->fetch_assoc())
    {

    }


?>



