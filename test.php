<?php
    include("data/mysql.lib.new.php");

    $lastname = 'Hattinger';

    $val = SQL::Row("SELECT * FROM users WHERE lastname = ?",'s',$lastname);

    echo $val['firstname'];

?>



