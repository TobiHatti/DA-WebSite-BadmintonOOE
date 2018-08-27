<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    echo '
        <style>
            *{
                font-family: Calibri;
            }
        </style>
    ';

    $y = $_GET['year'];
    $m = $_GET['month'];

    echo '<h1>'.strftime("%B %Y",strtotime($y.'-'.($m+1).'-0')).'</h1>';

    $dateStr = $y.'-'.str_pad($m, 2, "0", STR_PAD_LEFT).'-';
    $strSQL = "SELECT * FROM news WHERE release_date LIKE '$dateStr%'";
    $rs=mysqli_query($link,$strSQL);
    while($row=mysqli_fetch_assoc($rs))
    {
        echo '<a target="_parent" href="/news/artikel/'.$row['article_url'].'">'.$row['title'].'</a><br>';
    }


?>