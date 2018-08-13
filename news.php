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

    if(isset($_POST['publish']))
    {
        $id = $_POST['article_id'];
        $article = $_POST['article'];
        $tags = $_POST['tags'];
        $release = $_POST['release_date'];
        $thumb = $_POST['thumbnail'];
        $author = $_SESSION['user_id'];

        MySQLNonQuery("INSERT INTO news (id, author,tags,article,release_date,thumbnail) VALUES ('$id','$author','$tags','$article','$release','$thumb')") or die("<h1>Ein fehler ist aufgetreten</h1>");

        Redirect("/news/artikel/".$id);

    }


    if(isset($_GET['artikel']))
    {
        echo '<span style="color: #A9A9A9">'.date_format(date_create(fetch("news","release_date","id",$_GET['artikel'])),"d. F Y").'</span>|';

        foreach($tags = explode(';',fetch("news","tags","id",$_GET['artikel'])) as $tag)
        {
            if($tag != "" AND $tag != $tags[0]) echo ',&nbsp;&nbsp;<a href="/news/kategorie/'.$tag.'">'.$tag.'</a>';
            if($tag == $tags[0]) echo '&nbsp;&nbsp;<a href="/news/kategorie/'.$tag.'">'.$tag.'</a>';
        }

        echo '<div class="fr-view fr-element">'.fetch("news","article","id",$_GET['artikel']).'</div>';
    }
    else if(isset($_GET['neu']))
    {
        echo '
            <h2 class="stagfade1">Neuen Artikel verfassen</h2>
            <form action="/news?check" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <br>
                <div class="stagfade2">
                    Verfassen Sie den Neues Artikel in dem untenstehenden Textfeld:<br>
                    '.TextareaPlus("content","article","<h3>Artikel-Titel</h3>Hier den Artikel verfassen...").'
                    <br>
                    <hr>
                </div>
                <div class="stagfade3">
                    <h3>Gallerie-Fotos</h3>
                    W&auml;hlen Sie ein oder mehrere Fotos f&uuml;r diesen Bericht aus: <br><br>
                    '.FileButton('gallery_images', 'gimg',true).'
                    <br>
                    <hr>
                </div>
                <div class="stagfade4">
                    <h3>Tags</h3>
                        F&uuml;gen Sie dem Artikel Tags hinzu, um ihn schneller finden und sortieren zu k&ouml;nnen:<br><br>
                        <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>

                        <select onchange="TagList();">
                            <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                            <optgroup label="Hauptkategorien">
                                <option value="">Bundesliga</option>
                                <option value="">International</option>
                                <option value="">Nachwuchs</option>
                                <option value="">&Ouml;BV RLT</option>
                                <option value="">&Ouml;M</option>
                                <option value="">O&Ouml;BV RLT</option>
                                <option value="">O&Ouml;M</option>
                                <option value="">Top News</option>
                                <option value="">Verbandsintern</option>
                            </optgroup>
                        </select>

                        <input type="hidden" id="tag_nr" value="1"/>
                        <input type="hidden" id="tag_str" name="tags"/>

                        <div class="tag_container" id="tagContainer"></div>
                    <br>
                    <hr>
                </div>
                <div class="stagfade5">
                    <h3>Ver&ouml;ffentlichung</h3>
                    W&auml;hlen Sie den Zeitpunkt aus, zu dem der Artikel ver&ouml;ffentlicht werden soll (Standart: Sofort)<br>
                    <i>Format: [TT.MM.JJJJ]</i><br><br>
                    <input type="date" value="'.date("Y-m-d").'" id="relDate" name="release_date" class="cel_m"/>
                    <button type="button" onclick="document.getElementById(\'relDate\').value=\''.date("Y-m-d").'\'">&#128197; Heute</button>
                    <hr>
                </div>
                <div class="stagfade6">
                    <br><br>
                    <button type="submit">&#10148; Vorschau</button>
                </div>
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

        // Finds Thumbnail-Photo (Encoded in Base64)
        $imgs = substr($article,strpos($article,'src="'));
        $tnepos = strpos($imgs,'" ');
        $imgs = str_replace('src="','',substr($imgs,0,$tnepos));

        // Uploading images for Slideshow
        $dir = "content/news/".$nameid."/";
        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir);
            FileUpload($dir,"gallery_images");
        }

        // START OF PREVIEW

        echo '<h2 class="stagfade1">Artikel-Vorschau</h2><hr>';

        if($_FILES['gallery_images']['size'] == 0)
        {
            echo '<h2>Gallerie</h2><hr>';
        }

        echo '
            <span style="color: #A9A9A9">'.date_format(date_create($_POST['release_date']),"d. F Y").'</span>
            |';

            foreach($tags = explode(';',$_POST['tags']) as $tag)
            {
                if($tag != "" AND $tag != $tags[0]) echo ',&nbsp;&nbsp;<a href="#">'.$tag.'</a>';
                if($tag == $tags[0]) echo '&nbsp;&nbsp;<a href="#">'.$tag.'</a>';
            }

            echo '
            <div class="fr-view fr-element">'.$article.'</div>
            <hr>
            <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                <textarea name="article" style="display: none">'.$article.'</textarea>
                <input type="hidden" value="'.$_POST['tags'].'" name="tags"/>
                <input type="hidden" value="'.$_POST['release_date'].'" name="release_date"/>
                <input type="hidden" value="'.$nameid.'" name="article_id"/>
                <input type="hidden" value="'.$imgs.'" name="thumbnail"/>

                <button type="submit" name="publish">'.(($_POST['release_date'] == date("Y-m-d")) ? 'Jetzt ver&ouml;ffentlichen' : 'Am '.date_format(date_create($_POST['release_date']),"d.m.Y").' ver&ouml;ffentlichen').'</button>
            </form>
        ';
    }
    else
    {
        echo '<h1 class="stagfade1">Artikel-&Uuml;bersicht</h1>';
    }



    include("footer.php");
?>