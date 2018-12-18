<?php
    setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');

    require("headerincludes.php");

    echo '<head>';
    require("headerlinks.php");
    echo '</head><body>';



    $y = $_GET['year'];
    $m = $_GET['month'];
    $detail = $_GET['detail'];

    $dateStr = $y.'-'.str_pad($m, 2, "0", STR_PAD_LEFT).'-';

    $monthDisplay = ($m == 12) ? 1 : ($m+1);
    $yearDisplay = ($m == 12) ? ($y + 1) : $y;

    echo '<h1 class="stagfade1">'.str_replace('ä','&auml;',strftime("%B %Y",strtotime($yearDisplay.'-'.$monthDisplay.'-0'))).'</h1>';


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
                    '.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime($row['release_date']))).' - <a target="_parent" href="/news/artikel/'.$row['article_url'].'">'.$row['title'].'</a>
                </li>
            ';
        }
        echo '</ul>';
    }

    if(MySQL::Count("SELECT * FROM news WHERE release_date LIKE '$dateStr%'")==0)
    {
        echo '<h4>Keine Ergebnisse</h4>';
    }

    echo '</div></body>';


?>