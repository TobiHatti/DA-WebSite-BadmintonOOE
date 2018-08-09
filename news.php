<?php
    require("header.php");
    PageTitle("News");

    // NOTE:
    // HTACCESS CONTAINS SPECIAL CONFIGURATION FOR THIS PAGE!
    // A rewrite-rule is active, where
    // /news?artikel=xy
    // can be written as:
    // /news/artikel/xy
    // "xy" can be a value between "0-9", "a-z", "A-Z" or a "-"

    if(isset($_GET['artikel']))
    {
        echo '<h1 class="stagfade1">Artikel-Name</h1>';
    }
    else if(isset($_GET['neu']))
    {
        echo '
            <h2 class="stagfade1">Neuen Artikel verfassen</h2>
            <form action="/news?check" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <br>
                '.TextareaPlus("content","article","<h3>Artikel-Titel</h3>Hier den Artikel verfassen...","height:500px;").'
                <br>
                <button type="submit">Pr&uuml;fen und Fortfahren</button>
            </form>
        ';
    }
    else if(isset($_GET['check']))
    {
        $article = $_POST['content'];

        // Finds out the title of the article
        $posh1 = strpos($article,'</h1>');
        $posh2 = strpos($article,'</h2>');
        $posh3 = strpos($article,'</h3>');
        $posh4 = strpos($article,'</h4>');
        $posh5 = strpos($article,'</h5>');
        $posbr = strpos($article,'</p>');
        $posp = strpos($article,'<br>');

        // Converts values to array, filters it and finds the right lenght of the title
        $cpos = min(array_filter(array(intval($posh1),intval($posh2),intval($posh3),intval($posh4),intval($posh5),intval($posbr),intval($posp))));

        // Remove HTML-Tags, exchange whitespaces and so on.
        $nameid = str_replace(' ','-',strip_tags(substr($article,0,$cpos)));
        $nameid = str_replace('&Auml;','Ae',$nameid);
        $nameid = str_replace('&auml;','ae',$nameid);
        $nameid = str_replace('&Ouml;','Oe',$nameid);
        $nameid = str_replace('&ouml;','oe',$nameid);
        $nameid = str_replace('&Uuml;','Ue',$nameid);
        $nameid = str_replace('&uuml;','ue',$nameid);
        $nameid = str_replace('&szlig;','ss',$nameid);

        // Finds Thumbnail-Photo
        $imgs = substr($article,strpos($article,'src="'));
        $tnepos = strpos($imgs,'" ');
        $imgs = str_replace('src="','',substr($imgs,0,$tnepos));

        echo $nameid;
        echo '<br>'.$imgs;

        echo '
            <img src="blob:https://production.wepen.at/7fbda8ad-b76e-4f2e-9ee3-aeaaced71579" alt="" />
            <h2 class="stagfade1">Artikel-Vorschau</h2>

            <hr>
            '.$article.'
            <hr>




        ';
    }
    else
    {
        echo '<h1 class="stagfade1">Artikel-&Uuml;bersicht</h1>';
    }



    include("footer.php");
?>