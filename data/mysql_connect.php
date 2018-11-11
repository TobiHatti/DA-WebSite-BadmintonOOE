<?php
    require("_mysqlConDat.php");
    $link = mysqli_connect(getenv("MYSQLDB_SERVER"),getenv("MYSQLDB_USERNAME"),getenv("MYSQLDB_PASSWORD"),getenv("MYSQLDB_DBNAME")) or die("MySQL Error (001)");
?>
