<?php
    require("header.php");
    PageTitle("News");

    // NOTE:
    // HTACCESS CONTAINS SPECIAL CONFIGURATION FOR THIS PAGE!
    // A rewrite-rule is active, where
    // /news?artikel=xy
    // can be written as:
    // /news/artikel/xy
    // /news/kategorie/xy
    // "xy" can be a value between "0-9", "a-z", "A-Z" or a "-"


    if(isset($_POST['publish']))
    {
        $article_url = $_POST['article_id'];
        $article = $_POST['article'];
        $tags = SReplace($_POST['tags']);
        $release = $_POST['release_date'];
        $thumb = $_POST['thumbnail'];
        $title = $_POST['title'];
        $author = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0 ;

        // In case the ID already exists, cycle
        if(MySQLExists("SELECT article_url FROM news WHERE article_url = '$article_url'"))
        {
            $i=2;
            do
            {
                $newID = $article_url.'-'.$i;
                $i++;
            }
            while(MySQLExists("SELECT article_url FROM news WHERE article_url = '$newID'"));

            $article_url = $newID;
        }

        MySQLNonQuery("INSERT INTO news (id,article_url,title, author,tags,article,release_date,thumbnail) VALUES ('','$article_url','$title','$author','$tags','$article','$release','$thumb')") or die("<h1>Ein fehler ist aufgetreten</h1>");

        // Changing the News-Slider in Index
        RefreshSliderContent();

        Redirect("/news/artikel/".$article_url);
        die();
    }


    if(isset($_GET['artikel']))
    {
        MySQLNonQuery("UPDATE news SET views = views + 1 WHERE article_url = '".$_GET['artikel']."'");

        echo '
            <div class="doublecol_singletile">
                <article>
                    <span style="color: #A9A9A9">'.date_format(date_create(Fetch("news","release_date","article_url",$_GET['artikel'])),"d. F Y").' |</span>
                    '.ShowTags(Fetch("news","tags","article_url",$_GET['artikel'])).'
                    <div class="fr-view fr-element">'.Fetch("news","article","article_url",$_GET['artikel']).'</div>
                    <span>'.Fetch("news","views","article_url",$_GET['artikel']).' Aufrufe</span>
                </article>
                <aside>
                    '.NewsSidebar() .'
                </aside>
            </div>
        ';

        MySQLNonQuery("UPDATE news SET views = views + 1 WHERE article_url = '".$_GET['artikel']."'");
    }
    else if(isset($_GET['kategorie']))
    {
        echo '
            <div class="doublecol_singletile">
                <article>
                    <h2 class="stagfade1">'.Fetch("news_tags","name","id",$_GET['kategorie']).'</h2>
                    <h5 class="stagfade2">Seite '.((isset($_GET['page'])) ? $_GET['page'] : 1 ).'</h5>
                    <br>
        ';

        $tag = $_GET['kategorie'];

        $today = date("Y-m-d");

        $entriesPerPage = GetProperty("PagerSizeNews");

        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        echo NewsTile("SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%$tag%' ORDER BY release_date DESC, id DESC LIMIT $offset,$entriesPerPage");
        echo Pager("SELECT * FROM news WHERE release_date <= '$today' AND tags LIKE '%$tag%'",$entriesPerPage);

        echo '
                </article>
                <aside>
                    '.NewsSidebar() .'
                </aside>
            </div>
        ';

    }
    else if(isset($_GET['neu']))
    {
        echo '
            <h2 class="stagfade1">Neuen Artikel verfassen</h2>
            <form action="/news?check" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <br>
                <div class="stagfade2">
                    Verfassen Sie den Neues Artikel in dem untenstehenden Textfeld:<br>
                    '.TextareaPlus("content","article","<h2>Artikel-Titel</h2>Hier den Artikel verfassen...").'
                    <br>
                    <hr>
                </div>

                <!--

                <div class="stagfade3">
                    <h3>Gallerie-Fotos</h3>
                    W&auml;hlen Sie ein oder mehrere Fotos f&uuml;r diesen Bericht aus: <br><br>
                    '.FileButton('gallery_images', 'gimg',true).'
                    <br>
                    <hr>
                </div>

                -->

                <div class="stagfade4">
                    <h3>Tags</h3>
                        F&uuml;gen Sie dem Artikel Tags hinzu, um ihn schneller finden und sortieren zu k&ouml;nnen:<br><br>
                        <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
                        oder
                        <select onchange="TagList();" id="tagList">
                            <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                            <optgroup label="Hauptkategorien">
                                ';
                                $strSQL = "SELECT * FROM news_tags";
                                $rs=mysqli_query($link,$strSQL);
                                while($row=mysqli_fetch_assoc($rs)) { echo '<option value="'.$row['name'].'">'.$row['name'].'</option>'; }
                                echo '
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
        $title = strip_tags(substr($article,0,$cpos));
        $nameid = SReplace(strip_tags(substr($article,0,$cpos)));

        $path = "content/news/$nameid/";
        $article = ArticleImgFilter($article,$path);

        // Finds Thumbnail-Photo
        $imgs = substr($article,strpos($article,'src="'));
        $tnepos = strpos($imgs,'" ');
        $imgs = str_replace('src="','',substr($imgs,0,$tnepos));


        /*
        // Uploading images for Slideshow
        $dir = "content/news/".$nameid."/";
        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir);
            FileUpload($dir,"gallery_images");
        }
        */

        // START OF PREVIEW

        echo '<h2 class="stagfade1">Artikel-Vorschau</h2><hr>';

        /*
        if($_FILES['gallery_images']['size'] == 0)
        {
            echo '<h2>Gallerie</h2><hr>';
        }
        */

        echo '
            <span style="color: #A9A9A9">'.date_format(date_create($_POST['release_date']),"d. F Y").'</span>
            |';

            foreach($tags = explode('||',$_POST['tags']) as $tag)
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
                <input type="hidden" value="'.$title.'" name="title"/>

                <button type="submit" name="publish">'.(($_POST['release_date'] == date("Y-m-d")) ? 'Jetzt ver&ouml;ffentlichen' : 'Am '.date_format(date_create($_POST['release_date']),"d.m.Y").' ver&ouml;ffentlichen').'</button>
            </form>
        ';
    }
    else if(isset($_GET['suche']))
    {
        if(isset($_POST['newsSearch']) AND $_POST['newsSearch']!='')
        {
            Redirect('/news?suche='.$_POST['newsSearch']);
            die();
        }

        $searchValue = ((isset($_GET['suche'])) ? $_GET['suche'] : '' );

        echo '
            <div class="doublecol_singletile">
                <article>
        ';

        if($searchValue != '')
        {
            echo '<h2 class="stagfade1">Suchergebnisse f&uuml;r <i>"'.$searchValue.'"</i></h2>';




            if(MySQLCount("SELECT id FROM news WHERE title LIKE '%$searchValue%'")!=0)
            {
                $today = date("Y-m-d");
                $entriesPerPage = GetProperty("PagerSizeNews");
                $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;


                echo NewsTile("SELECT * FROM news WHERE release_date <= '$today' AND title LIKE '%$searchValue%' ORDER BY release_date DESC, id DESC LIMIT $offset,$entriesPerPage");
                echo Pager("SELECT * FROM news WHERE release_date <= '$today' AND title LIKE '%$searchValue%'",$entriesPerPage);

            }
            else
            {
                echo '<br><br><i>Keine Ergebnisse gefunden.</i>';
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

        echo '
                </article>
                <aside>
                    '.NewsSidebar() .'
                </aside>
            </div>
        ';
    }
    else
    {
        echo '
            <div class="doublecol_singletile">
                <article>
                    <h1 class="stagfade1">Neueste News</h1>
                    <br>
        ';

        $today = date("Y-m-d");

        $entriesPerPage = GetProperty("PagerSizeNews");

        $offset = ((isset($_GET['page'])) ? $_GET['page']-1 : 0 ) * $entriesPerPage;

        echo NewsTile("SELECT * FROM news WHERE release_date <= '$today' ORDER BY release_date DESC, id DESC LIMIT $offset,$entriesPerPage");
        echo Pager("SELECT * FROM news WHERE release_date <= '$today'",$entriesPerPage);

        echo '
                </article>
                <aside>
                    '.NewsSidebar() .'
                </aside>
            </div>
        ';
    }



    include("footer.php");
?>