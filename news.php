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


    if(isset($_POST['publish']) AND CheckPermission("AddNews"))
    {
        $article_url = $_POST['article_id'];
        $article = $_POST['article'];
        $tags = SReplace($_POST['tags']);
        $release = $_POST['release_date'];
        $thumb = $_POST['thumbnail'];
        $title = $_POST['title'];
        $author = (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0 ;

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

    if(isset($_POST['updateArticle']) AND CheckPermission("EditNews"))
    {
        $id = $_POST['updateArticle'];
        $article = $_POST['article'];
        $tags = SReplace($_POST['tags']);
        $release = $_POST['release_date'];

        list($article,$nameid,$thumb,$title) = ArticlePreProcessRoutine($article);

        $strSQL = "UPDATE news SET title = '$title',article_url = '$nameid' , tags = '$tags', article = '$article', release_date = '$release', thumbnail = '$thumb' WHERE id = '$id'";
        MySQLNonQuery($strSQL);

        Redirect("/news/artikel/".$nameid);
        die();
    }


    if(isset($_GET['artikel']))
    {
        if(isset($_GET['editSC']) AND CheckPermission("EditNews"))
        {
            $id = $_GET['editSC'];
            $strSQL = "SELECT * FROM news WHERE id = '$id'";
            $rs=mysqli_query($link,$strSQL);
            while($row=mysqli_fetch_assoc($rs))
            {
                echo '
                    <h2 class="stagfade1">Artikel bearbeiten</h2>
                    <form action="/news" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                        <div class="stagfade2">
                            <hr>
                            <p>Verfassen Sie den neuen Artikel in dem untenstehenden Textfeld:</p>
                            '.TextareaPlus("article","content",$row['article'], true).'
                            <br>
                            <hr>
                        </div>

                        <div class="stagfade3">
                        <h3>Tags</h3>
                            F&uuml;gen Sie dem Artikel Tags hinzu, um ihn schneller finden und sortieren zu k&ouml;nnen:<br><br>
                            <input type="search" class="cel_l" id="tagText" placeholder="Tags eingeben... (Mit [Enter] best&auml;tigen)" onkeypress="return TagInsert(event)"/>
                            oder
                            <select onchange="TagList();" id="tagList">
                                <option value="none" disabled selected>--- Kategorie Ausw&auml;hlen ---</option>
                                <optgroup label="Hauptkategorien">
                                    ';
                                    $strSQLT = "SELECT * FROM news_tags";
                                    $rsT=mysqli_query($link,$strSQLT);
                                    while($rowT=mysqli_fetch_assoc($rsT)) { echo '<option value="'.$rowT['name'].'">'.$rowT['name'].'</option>'; }
                                    echo '
                                </optgroup>
                            </select>

                            <script>
                                window.onload = function () {
                                    LoadTags();
                                }
                            </script>

                            <input type="hidden" id="tag_nr" value="1"/>
                            <input type="hidden" id="tag_str" name="tags" value="'.$row['tags'].'"/>

                            <div class="tag_container" id="tagContainer"></div>
                            <br>
                            <hr>
                        </div>
                        <div class="stagfade4">
                            <h3>Ver&ouml;ffentlichung</h3>
                            W&auml;hlen Sie den Zeitpunkt aus, zu dem der Artikel ver&ouml;ffentlicht werden soll:<br>
                            <i>Format: [TT.MM.JJJJ]</i><br><br>
                            <input type="date" value="'.$row['release_date'].'" id="relDate" name="release_date" class="cel_m" required/>
                            <button type="button" onclick="document.getElementById(\'relDate\').value=\''.date("Y-m-d").'\'">&#128197; Heute</button>
                            <hr>
                        </div>
                        <div class="stagfade5">
                            <br><br>
                            <button type="submit" name="updateArticle" value="'.$row['id'].'">&#8635; Aktualisieren</button>
                        </div>
                    </form>
                ';
            }
        }
        else
        {
            MySQLNonQuery("UPDATE news SET views = views + 1 WHERE article_url = '".$_GET['artikel']."'");
            $id = Fetch("news","id","article_url",$_GET['artikel']);

            echo '
                <div class="doublecol_singletile">
                    <article>
                        <span style="color: #A9A9A9">'.str_replace('ä','&auml;',strftime("%d. %B %Y",strtotime(Fetch("news","release_date","article_url",$_GET['artikel'])))).' |</span>
                        '.ShowTags(Fetch("news","tags","article_url",$_GET['artikel'])).'
                        <div class="fr-view fr-element">'.Fetch("news","article","article_url",$_GET['artikel']).'</div>
                        <span>'.Fetch("news","views","article_url",$_GET['artikel']).' Aufrufe</span><br><br>
                        ';

                        if(CheckPermission("EditNews"))
                        {
                            echo '<span style="float: left;"> '.EditButton(ThisPage("!editContent","!editSC","+editSC=$id")).' </span>';
                        }

                        if(CheckPermission("DeleteNews"))
                        {
                            echo '<span style="float: left;"> '.DeleteButton("News","news",$id).' </span>';
                        }

                        echo '
                    </article>
                    <aside>
                        '.NewsSidebar() .'
                    </aside>
                </div>
            ';
        }
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
    else if(isset($_GET['neu']) AND CheckPermission("AddNews"))
    {
        echo '
            <h2 class="stagfade1">Neuen Artikel verfassen</h2>
            <hr>
            <form action="/news?check" method="post" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                <div class="stagfade2">
                    <p>Verfassen Sie den neuen Artikel in dem untenstehenden Textfeld:</p>
                    <a href="#imageMaker"><button type="button" style="float: right; margin-top: -40px;">Bild-Ersteller</button></a>
                    '.TextareaPlus("content","article","<h2>Artikel-Titel</h2>Hier den Artikel verfassen...",true).'
                    <br>
                    <hr>
                </div>

                <div class="modal_wrapper" id="imageMaker">
                    <a href="#c">
                        <div class="modal_bg"></div>
                    </a>
                    <div class="modal_container" style="width: 50%; height: 60%;">
                        <h3>Bild-Ersteller</h3>

                        Vorlagen:
                        <select onchange="ChangeNewsImageCreatorLink();" id="themeSelector">
                            <option value="" selected disabled>--- Ausw&auml;hlen ---</option>
                            ';
                            $strSQL = "SELECT * FROM news_templates ORDER BY name ASC";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs))
                            {
                                echo '<option value="'.$row['id'].'">'.$row['name'].'</option> ';
                            }
                            echo '
                        </select>
                         oder: 
                        <a href="/news-vorlagen">Neue Vorlage erstellen</a>
                        <br><br>
                        <iframe src="/newsimagecreator" frameborder="0" style="width: 100%; height: 88%;" id="imageCreatorFrame"></iframe>



                    </div>
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

                <div class="stagfade3">
                    <h3>Tags</h3>
                        F&uuml;gen Sie dem Artikel Tags hinzu, um ihn schneller finden und sortieren zu k&ouml;nnen:<br><br>
                        '.TagSelector('tags').'
                    <br>
                    <hr>
                </div>
                <div class="stagfade4">
                    <h3>Ver&ouml;ffentlichung</h3>
                    W&auml;hlen Sie den Zeitpunkt aus, zu dem der Artikel ver&ouml;ffentlicht werden soll (Standart: Sofort)<br>
                    <i>Format: [TT.MM.JJJJ]</i><br><br>
                    <input type="date" value="'.date("Y-m-d").'" id="relDate" name="release_date" class="cel_m"/>
                    <button type="button" onclick="document.getElementById(\'relDate\').value=\''.date("Y-m-d").'\'">&#128197; Heute</button>
                    <hr>
                </div>
                <div class="stagfade5">
                    <br><br>
                    <button type="submit">&#10148; Vorschau</button>
                </div>
            </form>
        ';
    }
    else if(isset($_GET['check']) AND CheckPermission("AddNews") )
    {
        $article = $_POST['content'];

        list($article,$nameid,$imgs,$title) = ArticlePreProcessRoutine($article);

        // START OF PREVIEW

        echo '<h2 class="stagfade1">Artikel-Vorschau</h2><hr>';

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