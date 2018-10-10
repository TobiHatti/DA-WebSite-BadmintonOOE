<?php
    require("header.php");

    echo '
        <h1>Spielerreihung</h1>
        <hr>
        <h3>Aktuelle Reihung</h3>
    ';

    $club = Fetch("users","club","id",$_SESSION['userID']);
    $strSQL = "SELECT * FROM reihung WHERE type='H' AND club='$club'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {

    }

    echo '<a href="/spielerreihung/bearbeiten">Reihung bearbeiten</a>';

    include("footer.php");
?>