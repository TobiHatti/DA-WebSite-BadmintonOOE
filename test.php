<?php
    include("header.php");


    $string = "SELECT * FROM database WHERE this LIKE That";
    $posOfFrom = strpos($string,'FROM ') + strlen('FROM ');
    $posOfWhere = strpos($string,' WHERE');
    $db = substr($string,$posOfFrom,$posOfWhere-$posOfFrom);


    include("footer.php");

?>



