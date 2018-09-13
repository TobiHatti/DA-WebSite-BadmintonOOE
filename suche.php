<?php
    require("header.php");

    if(!isset($_GET['suche']))
    {
        Redirect("/suche/Alle/".$_POST['searchVal']);
        die();
    }
    else
    {
        $searchValue = ((isset($_GET['suche'])) ? $_GET['suche'] : '' );

        if($searchValue != '')
        {
            echo '<h2 class="stagfade1">Suchergebnisse f&uuml;r <i>"'.$searchValue.'"</i></h2>';


            if($_GET['kategorie'] AND $_GET['kategorie'] == 'Alle')
            {
                $strSQL = "
                    SELECT
                    id AS isNews, NULL AS isZA, NULL AS isFotogalerie, NULL AS isAgenda,
                    title AS searchField
                    FROM news
                    WHERE title LIKE '%$searchValue%'
                    UNION ALL
                    SELECT
                    NULL AS isNews, id AS isZA, NULL AS isFotogalerie, NULL AS isAgenda,
                    CONCAT_WS(' ', title_line1, title_line2) AS searchField
                    FROM zentralausschreibungen
                    WHERE CONCAT_WS(' ', title_line1, title_line2) LIKE '%$searchValue%'
                    UNION ALL
                    SELECT
                    NULL AS isNews, NULL AS isZA, id AS isFotogalerie, NULL AS isAgenda,
                    album_name AS searchField
                    FROM fotogalerie
                    WHERE album_name LIKE '%$searchValue%'
                    UNION ALL
                    SELECT
                    NULL AS isNews, NULL AS isZA, NULL AS isFotogalerie, id AS isAgenda,
                    CONCAT_WS(' ', titel, description) AS searchField
                    FROM agenda
                    WHERE CONCAT_WS(' ', titel, description) LIKE '%$searchValue%'
                    ORDER BY searchField ASC
                ";
            }
            else if($_GET['kategorie'] AND $_GET['kategorie'] == 'News')
            {
                $strSQL = "
                    SELECT
                    id AS isNews, NULL AS isZA, NULL AS isFotogalerie, NULL AS isAgenda,
                    title AS searchField
                    FROM news
                    WHERE title LIKE '%$searchValue%'
                    ORDER BY searchField ASC
                ";
            }
            else if($_GET['kategorie'] AND $_GET['kategorie'] == 'Zentralausschreibungen')
            {
                $strSQL = "
                    SELECT
                    NULL AS isNews, id AS isZA, NULL AS isFotogalerie, NULL AS isAgenda,
                    CONCAT_WS(' ', title_line1, title_line2) AS searchField
                    FROM zentralausschreibungen
                    WHERE CONCAT_WS(' ', title_line1, title_line2) LIKE '%$searchValue%'
                    ORDER BY searchField ASC
                ";
            }
            else if($_GET['kategorie'] AND $_GET['kategorie'] == 'Fotogalerie')
            {
                $strSQL = "
                    SELECT
                    NULL AS isNews, NULL AS isZA, id AS isFotogalerie, NULL AS isAgenda,
                    album_name AS searchField
                    FROM fotogalerie
                    WHERE album_name LIKE '%$searchValue%'
                    ORDER BY searchField ASC
                ";
            }
            else if($_GET['kategorie'] AND $_GET['kategorie'] == 'Kalender')
            {
                $strSQL = "
                    SELECT
                    NULL AS isNews, NULL AS isZA, NULL AS isFotogalerie, id AS isAgenda,
                    CONCAT_WS(' ', titel, description) AS searchField
                    FROM agenda
                    WHERE CONCAT_WS(' ', titel, description) LIKE '%$searchValue%'
                    ORDER BY searchField ASC
                ";
            }
            else Redirect("/suche/Alle/".$_GET['suche']);

             echo '
                <div style="float: right">
                Suchen in:
                <select id="ChaneSearchSubject" onchange="SetSearchRange();">
                    <option '.(($_GET['kategorie']=='Alle') ? 'selected' : '' ).' value="Alle">Alle</option>
                    <option '.(($_GET['kategorie']=='News') ? 'selected' : '' ).' value="News">News</option>
                    <option '.(($_GET['kategorie']=='Zentralausschreibungen') ? 'selected' : '' ).' value="Zentralausschreibungen">Zentralausschreibungen</option>
                    <option '.(($_GET['kategorie']=='Fotogalerie') ? 'selected' : '' ).' value="Fotogalerie">Fotogalerie</option>
                    <option '.(($_GET['kategorie']=='Kalender') ? 'selected' : '' ).' value="Kalender">Kalender</option>
                </select>
                <input type="hidden" id="seachVal" value="'.$searchValue.'"/>
                </div>
                <br><br>
            ';


            if(MySQLCount($strSQL)==0)
            {
                echo '<br><br><i>Keine Ergebnisse gefunden.</i>';
            }
            else
            {
                $today = date("Y-m-d");
                $entriesPerPage = GetProperty("PagerSizeSearch");
                $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;





                $rs=mysqli_query($link,$strSQL);
                while($row=mysqli_fetch_assoc($rs))
                {
                    if($row['isNews'] != NULL)
                    {
                        echo SeachTile("News",$row['isNews']);
                    }
                    else if($row['isZA'] != NULL)
                    {
                        echo SeachTile("Zentralausschreibungen",$row['isZA']);
                    }
                    else if($row['isFotogalerie'] != NULL)
                    {
                        echo SeachTile("Fotogalerie",$row['isFotogalerie']);
                    }
                    else if($row['isAgenda'] != NULL)
                    {
                        echo SeachTile("Kalender",$row['isAgenda']);
                    }
                }

                echo Pager($strSQL,$entriesPerPage);
            }
        }
        else
        {
            echo '
                <div style="text-align:center;">
                    <h1 class="stagfade1">Kein Suchwert gegeben</h1>
                    <br>
                    <h2 class="stagfade2">Bitte geben Sie einen Suchwert in der Suchleiste ein.</h2>
                </div>
            ';
        }

    }

    include("footer.php");
?>