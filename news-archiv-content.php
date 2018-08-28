<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
    require("data/mysql_connect.php");

    require("data/extension.lib.php");
    require("data/file.lib.php");
    require("data/mysql.lib.php");
    require("data/property.lib.php");
    require("data/string.lib.php");

    require("data/functions.php");

    echo '
        <head>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        </head>

    ';



    $y = $_GET['year'];
    $m = $_GET['month'];
    $detail = $_GET['detail'];

    $dateStr = $y.'-'.str_pad($m, 2, "0", STR_PAD_LEFT).'-';

    echo '<h1 class="stagfade1">'.strftime("%B %Y",strtotime($y.'-'.($m+1).'-0')).'</h1>';


    echo '<div class="iframe_content stagfade2">';

    if($detail)
    {
        echo NewsTile("SELECT * FROM news WHERE release_date LIKE '$dateStr%'",true);
    }
    else
    {
        echo '<ul>';
        $strSQL = "SELECT * FROM news WHERE release_date LIKE '$dateStr%'";
        $rs=mysqli_query($link,$strSQL);
        while($row=mysqli_fetch_assoc($rs))
        {
            echo '
                <li>
                    <a target="_parent" href="/news/artikel/'.$row['article_url'].'">'.$row['title'].'</a>
                </li>
            ';
        }
        echo '</ul>';
    }

    echo '</div>';


?>