<?php
    include("header.php");


    MySQLNonQuery("INSERT INTO test (id,value) VALUES ('','Hi')");

    $value = "Ho";
    $value2 = "He";

    //MySQLNonQuery("INSERT INTO test (id,value,value2) VALUES ('',?,?)","@s",$value,$value2);



    include("footer.php");



















?>



